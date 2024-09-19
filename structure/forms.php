<?php
class forms{
    public function sign_up_form(){
        ?>
            <div class="row align-items-md-stretch">
            <div class="col-md-8">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Fullname: </label>
                        <input type="text" name="fullname" class="form-control form-control-lg" id="fullname" placeholder="Enter your name" maxlength="50" autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address: </label>
                        <input type="email" name="fullname" class="form-control form-control-lg" id="email" placeholder="Enter your email" maxlength="50">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        <?php
    }
}