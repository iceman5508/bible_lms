<div class="certificate-frame sticky-top"
    style="background-image: url('{{asset($certificate->image)}}'); background-size: 100% auto; background-repeat: no-repeat; background-position: top; height:100%">
    <div style="position: relative; font-family: 'Mongolian Baiti';">

        @if($certificate->show_number == 'yes')
        <p class="certificate-number"
            style=" position: relative; top: {{$certificate->number_y_position? : 10}}px; left: {{$certificate->number_x_position? : 0}}px; text-align: center; color: {{$certificate->number_font_color ? : '#363234'}};font-size: {{$certificate->number_font_size ? : 20}}px; ">
            [Certificate Number]</p>
        @endif

        <div class="certificate-title"
            style=" position: relative; top: {{$certificate->title_y_position? : 10}}px;  left: {{$certificate->title_x_position? : 0}}px; text-align: center; font-size: {{$certificate->title_font_size}}px; font-weight: 400; color: {{$certificate->title_font_color ? : '#363234'}}">
            {{$certificate->title}}</div>

        @if($certificate->show_date == 'yes')
        <div class="certificate-publish-date"
            style="padding-top: 40px; text-align: center; position: relative; top: {{$certificate->date_y_position? : 10}}px; left: {{$certificate->date_x_position? : 0}}px; font-size: {{$certificate->date_font_size? : 30}}px; font-weight: 400; color: {{$certificate->date_font_color? : '#363234'}}; padding-bottom: 16px">
            [Certificate Date]</div>
        @endif
        @if($certificate->show_student_name == 'yes')
        <div class="certificate-student-name"
            style="text-align: center; position: relative; top: {{$certificate->student_name_y_position? : 10}}px; left: {{$certificate->student_name_x_position? : 0}}px; font-size: {{$certificate->student_name_font_size? : 32}}px; font-weight: 400;  color: {{$certificate->student_name_font_color? : '#363234'}}; padding-bottom: 16px">
            [Student Name]</div>
        @endif
        <p class="certificate-content"
            style="padding-bottom: 24px; min-height:220px; line-height: 30px; position: relative; top: {{$certificate->body_y_position? : 16}}px; left: {{$certificate->body_x_position? : 0}}px; text-align: center; padding-left: 128px; padding-right: 128px; color: {{$certificate->body_font_color? : '#363234'}}; font-size: {{$certificate->body_font_size? : 20}}px;">
            {{$certificate->body}}</p>
        @if($certificate->role_2_show == 'yes')
        <div class="certificate-signature certificate-signature-1"
            style="position: relative; text-align:center; position: relative;  top: {{$certificate->role_2_y_position? : 20}}px; left: {{$certificate->role_2_x_position? : 20}}px; ">
            <p
                style=" font-size: {{$certificate->role_2_font_size? : 18}}px; font-weight: 400; color: {{$certificate->role_2_font_color ? : '#363234'}}">
                {{$certificate->role_2_title}}</p>
        </div>
        @endif
        @if($certificate->role_1_show == 'yes')
        <div class="certificate-signature certificate-signature-2"
            style="position: relative; text-align:center;  position: relative;  top: {{$certificate->role_1_y_position? : 20}}px; left: {{$certificate->role_1_x_position? : 20}}px;">
            <div style="position: relative;">
                <img src="{{asset($certificate->role_1_signature)}}" alt="Signature 2">
            </div>
            <p
                style="font-size: {{$certificate->role_1_font_size? : 18}}px; font-weight: 400; color: {{$certificate->role_1_font_color ? : '#363234'}}">
                {{$certificate->role_1_title}}</p>
        </div>
        @endif
    </div>
</div>