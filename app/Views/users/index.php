<section>
    <div class="container mt-3">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-7">
                <h1 class="mb-1 font-weight-normal text-muted display-6">User Registration</h1>
                <?= session()->getFlashdata('error') ?>
                <?= validation_list_errors() ?>
                <?= form_open('users/index/' . $role['id'], 'post', ['id' => 'registerForm', 'class' => 'form-inline', 'role' => 'form']); ?>
                    <?= csrf_field() ?>
                    <div class="form-group mb-4">
                        <hr class="hr-light">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="name" name="firstName" placeholder="First Name" required="true">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required="true">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" id="userEmail" placeholder="Email Address" required="true">
                        </div>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="userPassword" name="password" placeholder="Password" required="true">
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <input type="text" class="form-control" name="address1" id="userAdress1" placeholder="Address" required="true">
                    </div>
                    <div class="form-group mt-4">
                        <input type="text" class="form-control" name="address2" id="userAdress2" placeholder="Address 2" required="true">
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <!-- Generate dropdown options dynamically from the $countries array -->
                            <select name="countryId" class="form-control" id="userCountry" required="true">
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?php echo $country['id'] ?>"><?php echo $country['name'] ?></option>
                                <?php endforeach;  ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="city" id="userCity" placeholder="City" required="true">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="postalCode" id="usersPostalCode" placeholder="Postal Code" required="true">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="phone" id="userPhone" placeholder="Phone Number" required="true">
                        </div>
                    </div>
                    <div class="form-group pt-3">
                        <hr class="hr-light">
                    </div>
                    <div class="form-group mt-3 pt-2 col-3">
                        <input type="submit" class="btn btn-secondary" id="btnSubmit" value="Sign Up">
                    </div>
                <?= form_close(); ?>
                <div class="mt-2">
                    <p>Already have an account? <a href="<?= site_url('/') ?>" class="link-primary">Login here!</a></p>
                </div>
            </div>
        </div>
</section>
<script src="js/validateSignUp.js"></script>