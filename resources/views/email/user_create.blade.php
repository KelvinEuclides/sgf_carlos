<p>{{__('Hi ')}} {{$user->name}}, {{__(' Thank you for joining to '). (isset(\Utility::settings()['company_name']) && !empty(\Utility::settings()['company_name']))?\Utility::settings()['company_name']:env('APP_NAME').' as a '.$user->type}}</p>
<p>{{__('To login your account detail, simply click on the button below:')}}</p>

<p><b>{{__('Username')}} : </b> {{$user->email}}</p>
<p><b>{{__('Password')}} : </b> {{$user->password}}</p>
