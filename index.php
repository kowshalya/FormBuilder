<?php
require_once 'lib/formBuilder.class.php'; //include the Form Builder library
require_once "lib/formvalidator.php"; //include the validation library
?>
<?php
$validator = new FormValidator();
if (isset($_POST) && !empty($_POST)) {// Once The form is submitted
    //Setup Validations
    $validator->addValidation("username", "req", "Please fill username");
    $validator->addValidation("username", "minlen=6", "username should be minimum 6 digits only");

    $validator->addValidation("email", "req", "Please fill Email");
    $validator->addValidation("email", "email", "The input for Email should be a valid email value");

    $validator->addValidation("password", "req", "Please fill Password");
    $validator->addValidation("password", "minlen=6", "Password should be minimum 6 digits only");

    $validator->addValidation("confirm_password", "req", "Please fill Confirm Password");
    $validator->addValidation("confirm_password", "eqelmnt=password", "Confirm Password Not Matched");

    $validator->addValidation("gender", "req", "Please choose gender");
    $validator->addValidation("hobbies", "req", "Please choose hobbies");

    //Now, validate the form
    if ($validator->ValidateForm()) {
        //Validation success. 
        //Here we can proceed with processing the form 
        //(like  saving to Database etc)
        // Here for example, displayig only a message
        echo "<div class='col-md-12 top-alert-block alert-success'><h4>Validation Success!</h4></div>";
    } else {
        ?>
        <div class="col-md-12 top-alert-block alert-danger">
            <?php
            echo "<h4>Validation Errors:</h4>";

            $error_hash = $validator->GetErrors();
            foreach ($error_hash as $inputname => $inp_error) {
                echo "<p><label>$inputname</label> : $inp_error</p>\n";
            }
            ?>
        </div>
        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <title>PHP Form Library</title>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/override.css" rel="stylesheet">
    </head>
    <body>
        <div class="row">
            <header>
                <nav class="navbar navbar-inverse navbar-fixed-top">
                    <div class="container">
                        <div class="navbar-header">                            
                            <a href="#" class="navbar-brand">Form library</a>
                        </div>                         
                    </div>
                </nav>
            </header> 
            <div class="content-wrapper container" >
                <!-- Example row of columns -->

                <div class="row">
                    <div class="page-header">
                        <h3>PHP Form Library</h3>

                    </div>


                    <?php
                    //initilize the formbuilder class
                    $form = new formbuilder(
                            "post", [ "class" => "form", "action" => "index.php"], 'userform');
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->input('User Name *', array('name' => 'username', 'type' => 'text', 'class' => 'form-control')); ?>
                        </div>

                        <div class="col-md-6"> 
                            <?= $form->input('Email *', array('name' => 'email', 'type' => 'text', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->input('Password *', array('name' => 'password', 'type' => 'password', 'class' => 'form-control')); ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->input('Confirm Password *', array('name' => 'confirm_password', 'type' => 'password', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"> 
                            <?=
                            $form->select('Select Grade *', //select syntax	
                                    array('class' => 'form-control', 'name' => 'grade'), array('Option1' => 'A', 'Option2' => 'B', 'Option3' => array('value' => 'C', 'selected' => 'selected')));
                            ?></div>
                        <div class="col-md-6"> 
                            <?=
                            $form->select('Select Car *', array('class' => 'form-control', 'name' => 'brand'), array('Cars' => array('bentley' => 'A', 'volkswagon' => 'B', 'Audi' => array('value' => 'c', 'selected' => 'selected')),
                                'Bikes' => array('yamaha' => 'x', 'harley davidson' => 'y', 'hero' => 'z')), true);
                            ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->radio('Gender *', array('Male' => 'M', 'Female' => 'F'), array('name' => 'gender')); ?>
                        </div>

                        <div class="col-md-6">  
                            <?= $form->check('Hobbies *', array('Video Games', 'Music', 'Swimming'), array('name' => 'hobbies')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->textarea('Description', array('rows' => '5', 'name' => 'summary', 'class' => 'form-control')); ?>
                        </div>
                    </div>

                    <div class="row text-right">
                        <div class="col-md-12">
                            <?= $form->submit('Submit', array('name' => 'userform', 'class' => 'btn btn-success')); ?>
                        </div>
                    </div>
                    <?php $form->end(); ?>
                </div>
            </div>
        </div>
        <footer>
            <div class="text-center">
                <p>Copyright 2016</p>
            </div>
        </footer>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>