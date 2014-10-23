<?php echo form_open('', array('class'=>'form-signin')); ?>

    <?php echo form_input(array('name'=>'username', 'id'=>'username', 'class'=>'form-control', 'placeholder'=>lang('users input username_email'))); ?>
    <?php echo form_password(array('name'=>'password', 'id'=>'password', 'class'=>'form-control', 'placeholder'=>lang('users input password'), 'autocomplete'=>'off')); ?>
    <?php echo form_submit(array('name'=>'submit', 'class'=>'btn btn-lg btn-primary btn-block'), lang('core button login')); ?>

    <p><br /><a href="<?php echo base_url('forgot'); ?>"><?php echo lang('users link forgot_password'); ?></a></p>
    <p><a href="<?php echo base_url('register'); ?>"><?php echo lang('users link register_account'); ?></a></p>

<?php echo form_close(); ?>
