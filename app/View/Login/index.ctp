<form method="post" id="LoginForm">
    <table width="330px" height = "300px">
        <tr>
            <td align = "center">
                <h1>Login</h1>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="UserName"  class="form-control col-md-7 col-xs-12" id="UserName" placeholder="Username" onkeypress="handle(event)"/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="password" name="Password" class="form-control col-md-7 col-xs-12" id="Password" placeholder="Password" onkeypress="handle(event)"/>
            </td>
        </tr>
        <tr>
            <td>
                <button type="button" style="height:45px"  class="btn btn-primary btn-block btn-large" onclick = "Login()">Sign In !</button>
            </td>
        </tr>
        <tr>
            <td height = "40px">
                <div id="fountainG" style="display:none;">
                    <div id="fountainG_1" class="fountainG"></div>
                    <div id="fountainG_2" class="fountainG"></div>
                    <div id="fountainG_3" class="fountainG"></div>
                    <div id="fountainG_4" class="fountainG"></div>
                    <div id="fountainG_5" class="fountainG"></div>
                    <div id="fountainG_6" class="fountainG"></div>
                    <div id="fountainG_7" class="fountainG"></div>
                    <div id="fountainG_8" class="fountainG"></div>
                </div>
            </td>
        </tr>
    </table>
</form>
<table cellspacing = "0" cellpadding = "0" width="430px">
    <tr>    
        <td width = "50px" style = "border-top: 1px solid #1a2633 ;">
            &nbsp;
        </td>
        <td align = "center" style = "border-top: 1px solid #1a2633 ;">
            <br>
            SYNCWISE MEDREP MODULE<br>
            Version 1.0.0
        </td>
        <td width = "50px" style = "border-top: 1px solid #1a2633 ;">
            &nbsp;
        </td>
    </tr>
</table>
<script lang="javascript">
    function handle(event) {
        if (event.keyCode === 13) {
            Login();
        }
    }
</script>


