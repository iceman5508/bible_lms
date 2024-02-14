<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ __('Certificate') }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        *{
            box-sizing: border-box;
            margin: 0;
        }
    </style>
</head>
<body>

<div class="pdf-wrapper-box " style="font-familycertificateFont; padding: 10px; margin-top: 50px;">

    <div class="certificate-frame" style="font-familycertificateFont; background-image: url('{{public_path($certificate->image)}}'); background-size: 100% auto; background-repeat: no-repeat; background-position: center;">
        <div style="font-familycertificateFont; position: relative; font-family: 'Mongolian Baiti';  padding: 35px  0;">

            @if($certificate->show_number == 'yes')
            <p class="certificate-number" style="font-familycertificateFont;  position: relative; top: {{$certificate->number_y_position? : 10}}px; text-align: center; color: {{$certificate->number_font_color ? : '#363234'}};font-size: {{$certificate->number_font_size ? : 20}}px; ">{{$certificate->certificate_number}}</p>
            @endif

            <div class="certificate-title" style="font-familycertificateFont;  position: relative; top: {{$certificate->title_y_position? : 10}}px; text-align: center; font-size: {{$certificate->title_font_size}}px; font-weight: 400; color: {{$certificate->title_font_color ? : '#363234'}}">{{$certificate->title}}</div>

                @if($certificate->show_date == 'yes')
                    <div class="certificate-publish-date" style="font-familycertificateFont; padding-top: 40px; text-align: center; position: relative; top: {{$certificate->date_y_position? : 10}}px; font-size: {{$certificate->date_font_size? : 30}}px; font-weight: 400; color: {{$certificate->date_font_color? : '#363234'}}; padding-bottom: 16px">[Certificate Date]</div>
                @endif
                @if($certificate->show_student_name == 'yes')
            <div class="certificate-student-name" style="font-familycertificateFont; text-align: center; position: relative; top: {{$certificate->student_name_y_position? : 10}}px; font-size: {{$certificate->student_name_font_size? : 32}}px; font-weight: 400;  color: {{$certificate->student_name_font_color? : '#363234'}}; padding-bottom: 16px">[Student Name]</div>
                @endif
            <p class="certificate-content" style="font-familycertificateFont; padding-bottom: 24px; position: relative; top: {{$certificate->body_y_position? : 16}}px; text-align: center; padding-left: 128px; padding-right: 128px; color: {{$certificate->body_font_color? : '#363234'}}; font-size: {{$certificate->body_font_size? : 20}}px;">{{$certificate->body}}</p>

            <div style="font-familycertificateFont; padding-bottom: 11rem;">
                <div class="certificate-signature certificate-signature-1" style="font-familycertificateFont; padding-left: 140px; position: relative; bottom: 10px; float: left">
                    <div style="font-familycertificateFont; height: 60px;">

                    </div>
                    <p style="font-familycertificateFont;  position: relative;  top: {{$certificate->role_2_y_position? : 20}}px; font-size: {{$certificate->role_2_font_size? : 18}}px; font-weight: 400; color: {{$certificate->role_2_font_color ? : '#363234'}}">{{$certificate->role_2_title}}</p>
                </div>
                <div class="certificate-signature certificate-signature-2" style="font-familycertificateFont; padding-right: 140px; position: relative; bottom: 10px; float: right">
                    <div style="font-familycertificateFont; position: relative; top: 45px;">
                        <img src="{{public_path($certificate->role_1_signature)}}" alt="Signature 2">
                    </div>
                    <p style="font-familycertificateFont;  position: relative;  top: {{$certificate->role_1_y_position? : 20}}px; font-size: {{$certificate->role_1_font_size? : 18}}px; font-weight: 400; color: {{$certificate->role_1_font_color ? : '#363234'}}">{{$certificate->role_1_title}}</p>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
