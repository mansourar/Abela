<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            SYNCWISE | MONEY COLLECTION
        </title>
        <link rel="shortcut icon" type="image/x-icon" href="img/logo.png" />
        <?php
        //echo $this->Html->meta('icon');
        echo $this->Html->css('LoginPage');
        echo $this->Html->css('bootstrap.min');
        echo $this->Html->css('fonts/css/font-awesome.min');
        echo $this->Html->css('animate.min');
        echo $this->Html->css('custom');
        echo $this->Html->css('icheck/flat/green');
        echo $this->Html->css('progressbar/bootstrap-progressbar-3.3.0');
        echo $this->Html->script('jquery.min');
        echo $this->Html->script('nanobar.min');
        echo $this->Html->script('LoginPage');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>  
    </head>
    <body>
        <script lang="javascript">
            var nanobar = new Nanobar()
            var styleTag = document.getElementById('nanobarcss')
            styleTag.innerHTML += '.nanobar .bar{background:#1ABB9C; height:2px}'
        </script>
        <table width="100%" height = "95%" border = "0">
            <tr>
                <td style="padding: 20px" height = "100">
                    <!--<img src="img/logo.png" width="75"/>-->
                </td>
            </tr>
            <tr>
                <td align="center" valign="top">
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->fetch('content'); ?>
                </td>
            </tr>
        </table>
        <script lang="javascript">
            nanobar.go(70);
        </script>
        <?php
            echo $this->Html->script('bootstrap.min');
            echo $this->Html->script('notify/pnotify.core');
            echo $this->Html->script('notify/pnotify.buttons');
            echo $this->Html->script('notify/pnotify.nonblock');
        ?>
        <script lang="javascript">
            nanobar.go(100);
        </script>
    </body>
</html>