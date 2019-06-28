<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <style>
            .contain{
                width: 70%;
                margin:0px auto;
            }

            @media only screen and (max-width: 750px) {
                .contain{
                    width: 100%;
                }
            }
        </style>
    </head>
    <body style="background:#FAFAFA">
        <nav class="navbar navbar-expand-sm" style="background: #B71C1C"></nav>
        <div style="text-align: center; margin: 30px">
            <img src="logo_cerebrum.png" alt="logo cerebrum" width="380px">
        </div>
        <div style="padding:50px;background: #ffffff">
            <div class="contain">
                <h3>Halo!</h3>
                <span>Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi ke akun Anda.</span>
                
                <div style="text-align: center;margin:20px">
                    <a href="{{ $url }}" class="btn btn-primary" style="">Reset Password</a>
                </div>

                <span>Jika Anda tidak lupa kata sandi, Anda dapat dengan aman mengabaikan email ini.</span>
                <p>Terimakasih</p>
            </div>
        </div>

        <div style="text-align:center;padding:20px;color:#616161">
            <strong> PT Cerebrum Media Tama Indonesia</strong>
        </div>
    </body>
</html>
