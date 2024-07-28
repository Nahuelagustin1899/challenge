<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1991/xhtml">
    <head>
        @include( 'emails.template.head' )
    </head>
    <body>
        <center class="wrapper">
            <table class="main" width="100%">
                <tr>
                    <td style="padding: 25px;">
                        <table class="full_width" style="border: 1px solid #D6D6D6; border-radius: 8px; padding: 30px 25px;">
                            @include( 'emails.template.header' )
                            @yield( 'content' )
                            @include( 'emails.template.body_footer' )
                        </table>
                    </td>
                </tr>
                @include( 'emails.template.footer' )
            </table>
        </center>
    </body>
</html>
