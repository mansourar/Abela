<!DOCTYPE html>
<html lang="en">

    <head>
        <?php echo $this->Html->charset(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
            SYNCWISE | MEDREP PORTAL
        </title>
        <?php
        echo $this->Html->css('bootstrap.min');
        echo $this->Html->css('fonts/css/font-awesome.min');
        echo $this->Html->css('animate.min');
        echo $this->Html->css('custom');
        echo $this->Html->css('icheck/flat/green');
        echo $this->Html->css('progressbar/bootstrap-progressbar-3.3.0');

        echo $this->Html->css('nprogress');
        echo $this->Html->css('Jqgrid/ui.jqgrid-bootstrap');
        echo $this->Html->css('Jqgrid/ui.jqgrid-bootstrap-ui');

        echo $this->Html->script('jquery.min');
        echo $this->Html->script('nanobar.min');
        echo $this->Html->script('jquery.min');
        echo $this->Html->script('GridExtras');
        echo $this->Html->script('LoggedInExtras');
        echo $this->Html->script('jquery-ui.min');
        echo $this->Html->script('Jqgrid/i18n/grid.locale-en');
        echo $this->Html->script('Jqgrid/jquery.jqGrid.min');
        ?>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div style="width:230px">

                        </div>
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <img src="<?php echo($this->Session->read("ProfilePicture")); ?>" width="50" height ="50" alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>Welcome,</span>
                                <h2>
                                    <?php
                                    echo($this->Session->read('UserFirstName') . " " . $this->Session->read('UserLastName'));
                                    ?>
                                </h2>
                            </div>
                        </div>
                        <br />

                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <ul class="nav side-menu">
                                    <?php
                                    echo($this->Session->read('MenuString'));
                                    ?>
                                </ul>
                            </div>
                        </div>


                        <div class="sidebar-footer hidden-small">
                            <a data-toggle="tooltip" data-placement="top" title="Cycles" href="../Cycles/index">
                                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Brochures" href="../Brochures/index">
                                <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Reports" href="../Reports/index">
                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Logout" href="../Login">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                        </div>
                        <!-- /menu footer buttons -->



                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav class="" role="navigation">
                            <div class="nav toggle" style="padding-top:20px">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="<?php echo($this->Session->read("ProfilePicture")); ?>" alt="">
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                        <?php if ($this->Session->read("CanEditProfile") == "Y") { ?>
                                            <li>
                                                <a href="../Profile/index"><i class="fa fa-user-md pull-right"></i>Profile</a>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <a href="../Login"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <div style="" class="right_col" role="main" id="MainWebDiv"s>
                    <div class="" style = background-color: red>
                        <?php
                        echo $this->Flash->render();
                        echo $this->fetch('content');
                        ?>
                        <br />
                    </div>
                </div>
            </div>
        </div>

        <?php
//        echo $this->Html->script('bootstrap.min');
//        echo $this->Html->script('jquery.min');
//        echo $this->Html->script('bootstrap.min');
//        echo $this->Html->script("../js/nicescroll/jquery.nicescroll.min.js");
//        echo $this->Html->script('fastclick');
//        echo $this->Html->script('nprogress');
//        echo $this->Html->script('custom');
//        echo $this->Html->script("../js/datepicker/daterangepicker.js");
//        
//        echo $this->Html->script('notify/pnotify.core');
//        echo $this->Html->script('notify/pnotify.buttons');
//        echo $this->Html->script('notify/pnotify.nonblock');
        
        echo $this->Html->script("../js/bootstrap.min.js");
        echo $this->Html->script("../js/progressbar/bootstrap-progressbar.min.js");
        echo $this->Html->script("../js/nicescroll/jquery.nicescroll.min.js");
        echo $this->Html->script("../js/icheck/icheck.min.js");
        echo $this->Html->script("../js/moment/moment.min.js");
        echo $this->Html->script("../js/datepicker/daterangepicker.js");
        echo $this->Html->script("../js/tags/jquery.tagsinput.min.js");
        echo $this->Html->script('switchery/switchery.min');
        echo $this->Html->script("../js/custom.js");
        echo $this->Html->script("../js/flot/jquery.flot.js");
        echo $this->Html->script("../js/flot/jquery.flot.pie.js");
        echo $this->Html->script("../js/flot/jquery.flot.orderBars.js");
        echo $this->Html->script("../js/flot/jquery.flot.time.min.js");
        echo $this->Html->script("../js/flot/date.js");
        echo $this->Html->script("../js/flot/jquery.flot.spline.js");
        echo $this->Html->script("../js/flot/jquery.flot.stack.js");
        echo $this->Html->script("../js/flot/curvedLines.js");
        echo $this->Html->script('notify/pnotify.core');
        echo $this->Html->script('notify/pnotify.buttons');
        echo $this->Html->script('notify/pnotify.nonblock');
        echo $this->Html->script('LoggedInExtras');
        echo $this->Html->script('ion_range/ion.rangeSlider.min.js');
        echo $this->Html->script('calendar/fullcalendar.min');
        echo $this->Html->script("select/select2.full.js");

        echo $this->Html->script("../js/echart/echarts-all.js");
        echo $this->Html->script("../js/echart/green.js");
        ?>

        

    </body>

</html>