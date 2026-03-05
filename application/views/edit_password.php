<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>


<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>



<div class="container">
    <br />
    <br />
    <br />
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card  justify-content-center justify-items-center">
            <div class="card-body">
                <h4 class="card-title"> Edit Password</h4>

                <form class="forms-sample" method="post" action="<?php echo base_url('auth/do_edit_password') ?>">
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label"> Password Lama </label>
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control" id="exampleInputUsername2" placeholder="Password Lama">
                            <input type="text" hidden name="id_user" value="<?php echo $user->id ?>" class="form-control" id="exampleInputUsername2" placeholder="Username">
                            <input type="text" hidden name="username" value="<?php echo $user->username ?>" class="form-control" id="exampleInputUsername2" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label"> Password Baru </label>
                        <div class="col-sm-9">
                            <input type="password" name="passwordBaru" class="form-control" id="exampleInputUsername2" placeholder="Password Baru">
                            <small class="text-danger">password minimal 8 karakter dengan kombinasi huruf, angka dan karakter </small> <?= form_error('passsword') ?>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="<?php echo base_url('users') ?>" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>