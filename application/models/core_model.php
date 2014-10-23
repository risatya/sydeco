<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core_model extends CI_Model {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }


    /**
     * Get navigation items and build Bootstrap menu
     *
     * @param string $type
     * @param int $menu_id
     * @param $current_uri
     * @return string
     */
    function get_nav($type='public', $menu_id=2, $current_uri='/')
    {
        $sql = "
            SELECT *
            FROM navs
            WHERE type = " . $this->db->escape($type) . "
                AND parent_id = 0
                AND menu_id = " . $this->db->escape($menu_id) . "
            ORDER BY sort_order ASC
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            $menu = '<ul class="nav navbar-nav">';

            $results = $query->result_array();

            foreach ($results as $row)
            {
                $submenu = NULL;

                $sql = "
                    SELECT *
                    FROM navs
                    WHERE type = " . $this->db->escape($type) . "
                        AND parent_id = " . $this->db->escape($row['id']) . "
                        AND menu_id = " . $this->db->escape($menu_id) . "
                    ORDER BY sort_order ASC
                ";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0)
                    $submenu = $query->result_array();

                $active = FALSE;

                if ($current_uri == $row['url'])
                    $active = ' active';

                if (is_array($submenu))
                {
                    foreach ($submenu as $sub)
                    {
                        if (array_search($current_uri, $sub) !== FALSE)
                            $active = ' active';
                    }
                }

                $menu .= '<li class="' . ((is_array($submenu)) ? 'dropdown' : '') . $active . '">';
                $menu .= '<a href="' . base_url($row['url']) . '"' . ((is_array($submenu)) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '') . '>';
                $menu .= $row['title'] . ((is_array($submenu)) ? ' <b class="caret"></b>' : '') . '</a>';

                if (is_array($submenu))
                {
                    $menu .= '<ul class="dropdown-menu">';

                    foreach ($submenu as $sub)
                        $menu .= '<li class="' . (($current_uri == $sub['url']) ? 'active' : '') . '"><a href="' . base_url($sub['url']) . '">' . $sub['title'] . '</a></li>';

                    $menu .= '</ul>';
                }

                $menu .= '</li>';
            }

            $menu .= '</ul>';
        }
        else
            $menu = '';

        return $menu;
    }


    /**
     * Retrieve all settings
     *
     * @return array|null
     */
    function get_settings()
    {
        $sql = "
            SELECT *
            FROM settings
            ORDER BY sort_order ASC
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
            $results = $query->result_array();
        else
            $results = NULL;

        return $results;
    }


    /**
     * Save changes to the settings
     *
     * @param array $data
     * @param int $user_id
     * @return bool
     */
    function save_settings($data=array(), $user_id=NULL)
    {
        if (empty($data) || is_null($user_id))
            return FALSE;

        $saved = FALSE;

        foreach ($data as $key=>$value)
        {
            $sql = "
                UPDATE settings
                    SET value = " . $this->db->escape($value) . ",
                        last_update = '" . date('Y-m-d H:i:s') . "',
                        updated_by = " . $this->db->escape($user_id) . "
                WHERE name = " . $this->db->escape($key) . "
            ";

            $this->db->query($sql);

            if ($this->db->affected_rows() > 0)
                $saved = TRUE;
        }

        if ($saved)
            return TRUE;
        else
            return FALSE;
    }

}
