<form method="POST" id="ProfileForm" class="form-horizontal form-label-left" enctype="multipart/form-data" onsubmit = "return SaveProfileAction(this)"   >
    <div class="row" id = "ContentDivMod">
        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px; margin: 0px">
            <div class="row x_title">
                <div class="col-md-6">
                    <h3>Profile - <small>Profile Informations</small></h3>
                </div>
                <div class="col-md-6">
                    <!--ENTER CENTER TITLE-->
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="FirstName">
                            First Name 
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                id="FirstName" 
                                name="FirstName" 
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" 
                                data-validate-length-range="6" 
                                data-validate-words="2" 
                                name="name" 
                                placeholder="First Name" 
                                maxlength="50"
                                value = "<?php echo($FirstName); ?>"
                                type="text" />
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="MiddleName">
                            Middle Name 
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text" 
                                id="MiddleName" 
                                name="MiddleName"  
                                placeholder="Middle Name" 
                                maxlength="50"
                                value = "<?php echo($MiddleName); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="LastName">
                            Last Name 
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text" 
                                id="LastName" 
                                name="LastName" 
                                placeholder="Last Name"
                                maxlength="50"
                                value = "<?php echo($LastName); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="BirthDate">
                            Birth Date
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text"
                                id="BirthDate" 
                                name="BirthDate"
                                placeholder="E-mail"
                                maxlength="50"
                                readonly = ""
                                value = "<?php echo($BirthDate); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="Address">
                            Address 
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text" 
                                id="Address" 
                                name="Address" 
                                placeholder="Address"
                                maxlength="50"
                                value = "<?php echo($Address); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-location-arrow form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="Mobile">
                            Mobile 
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text" 
                                id="Mobile" 
                                name="Mobile" 
                                required="required" 
                                placeholder="Mobile"
                                maxlength="50"
                                value = "<?php echo($Mobile); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-mobile form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="Phone">
                            Phone 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text" 
                                id="Phone" 
                                name="Phone" 
                                placeholder="Phone"
                                maxlength="50"
                                value = "<?php echo($Phone); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="Fax">
                            Fax
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text" 
                                id="Fax" 
                                name="Fax" 
                                placeholder="Fax"
                                maxlength="50"
                                value = "<?php echo($Fax); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-fax form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="UserEmail">
                            Email
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="text" 
                                id="UserEmail" 
                                name="UserEmail"
                                placeholder="E-mail"
                                maxlength="50"
                                value = "<?php echo($Email); ?>"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-at form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="ProfilePic">
                            Profile Picture
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                            <input 
                                type="file" 
                                id="ProfilePic" 
                                name="ProfilePic" 
                                style="border: none"
                                class="form-control col-md-6 col-sm-6 col-xs-6 col-lg-6" />
                            <span class="fa fa-file form-control-feedback right" aria-hidden="true"></span>
                            <?php echo($ErrorMessage); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">
                    <div class="row">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Actions</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li>
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <button type="button" class="btn btn-danger" style="width:100%" id="EditEntityButton" onclick="window.location.href = '/Abela/Dashboard/index';" data-toggle="modal" data-target=".bs-example-modal-lg">Cancel</button>
                                <input type="submit" class="btn btn-success" style="width:100%" id="AddEntityButton"  value="Save"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</form>
<script lang="javascript">
    $(document).ready(function () {
        $('#BirthDate').daterangepicker(
                {
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    singleDatePicker: true,
                    showDropdowns: true,
                    calender_style: "picker_2",
                    startDate: '<?php echo($BirthDate); ?>',
                    endDate: '<?php echo($BirthDate); ?>'
                },
        function (start, end, label) {
            return false;
        });
        $('#ProfilePic').bind('change', function () {
            if (this.files[0].size > 1048576) {
                document.getElementById('ProfilePic').value = "";
                new PNotify({
                    title: 'Invalid File Size. ',
                    text: ' File Must Be Less Than 1 Mb !',
                    type: 'error',
                    delay: 1500
                });
            }
        });
    });
</script>