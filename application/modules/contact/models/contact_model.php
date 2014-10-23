<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_model extends CI_Model {

    /**
     * @vars
     */
    private $_db;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_db = 'emails';
    }


    /**
     * Save generated CAPTCHA to database
     *
     * @param array $data
     * @return bool
     */
    public function save_captcha($data=array())
    {
        // CAPTCHA data required
        if (empty($data))
            return FALSE;

        // insert CAPTCHA
        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);

        // return
        return TRUE;
    }


    /**
     * Verify CAPTCHA
     *
     * @param string $captcha
     * @return bool
     */
    public function verify_captcha($captcha=NULL)
    {
        // CAPTCHA string required
        if (is_null($captcha))
            return FALSE;

        // remove old CAPTCHA
        $expiration = time() - 7200; // Two hour limit
        $this->db->query("DELETE FROM captcha WHERE captcha_time < " . $expiration);

        // build query
        $sql = "
            SELECT
                COUNT(*) AS count
            FROM captcha
            WHERE word = " . $this->db->escape($captcha) . "
                AND ip_address = '" . $this->input->ip_address() . "'
                AND captcha_time > '{$expiration}'
        ";

        // execute query
        $query = $this->db->query($sql);

        // return results
        if ($query->row()->count == 0)
            return FALSE;
        else
            return TRUE;
    }


    /**
     * Save and email contact message
     *
     * @param array $data
     * @param array $settings
     * @return bool
     */
    public function save_and_send_message($data=array(), $settings=array())
    {
        // post data and settings required
        if (empty($data) || empty($settings))
            return FALSE;

        // build query
        $sql = "
            INSERT INTO {$this->_db} (
                name, email, title, message, created
            ) VALUES (
                " . $this->db->escape($data['name']) . ",
                " . $this->db->escape($data['email']) . ",
                " . $this->db->escape($data['title']) . ",
                " . $this->db->escape($data['message']) . ",
                '" . date('Y-m-d H:i:s') . "'
            )
        ";

        // execute query
        $this->db->query($sql);

        if ($id = $this->db->insert_id())
        {
            // send email
            $this->email->from($data['email'], $data['name']);
            $this->email->to($settings->site_email);
            $this->email->subject($data['title']);
            $this->email->message($data['message']);
            $send_mail = $this->email->send();
            #echo $this->email->print_debugger();

            if ( ! $send_mail)
            {
                // send mail failed - remove message from database
                $this->db->query("DELETE FROM {$this->_db} WHERE id = {$id}");
                return FALSE;
            }
            else
                return TRUE;
        }
        else
            return FALSE;
    }


    /**
     * Get list of non-deleted users
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @param string $sort
     * @param string $dir
     * @return array|bool
     */
    function get_all($limit=0, $offset=0, $filters=array(), $sort='created', $dir='DESC')
    {
        // start building query
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS *
            FROM {$this->_db}
            WHERE 1 = 1
        ";

        // apply filters
        if ( ! empty($filters))
        {
            foreach ($filters as $key=>$value)
            {
                $value = $this->db->escape('%' . $value . '%');
                $sql .= " AND {$key} LIKE {$value}";
            }
        }

        // continue building query
        $sql .= " ORDER BY {$sort} {$dir}";

        // add limit and offset
        if ($limit)
        {
            $offset = (int)$offset;
            $limit  = (int)$limit;
            $sql .= " LIMIT {$offset}, {$limit}";
        }

        // execute query
        $query = $this->db->query($sql);

        // define results
        if ($query->num_rows() > 0)
            $results['results'] = $query->result_array();
        else
            $results['results'] = NULL;

        // get total count
        $sql = "SELECT FOUND_ROWS() AS total";
        $query = $this->db->query($sql);
        $results['total'] = $query->row()->total;

        // return results
        return $results;
    }


    /**
     * Set email message as read
     *
     * @param int $id
     * @param int $read_by
     * @return bool
     */
    public function read($id=NULL, $read_by=NULL)
    {
        // data required
        if (is_null($id) || is_null($read_by))
            return FALSE;

        // build query string
        $sql = "
            UPDATE {$this->_db}
            SET `read` = '" . date('Y-m-d H:i:s') . "',
                read_by = {$read_by}
            WHERE id = {$id}
        ";

        // execute query
        $this->db->query($sql);

       // return results
        if ($this->db->affected_rows())
            return TRUE;
        else
            return FALSE;
    }

}
