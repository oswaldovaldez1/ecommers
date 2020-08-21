<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,200;0,400;0,600;1,200;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@500;700&display=swap" rel="stylesheet">
    {{--  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">  --}}
    <link rel="icon" href="{{url('/')}}/img/Lanesalogo.svg">
    <link href="{{url('/')}}/css/app.css" rel="stylesheet">
    <title>Lanesa1957</title>
    <script src="https://apis.google.com/js/api:client.js"></script>
       <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vuetify@1.5.6/dist/vuetify.min.css" />
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.1/tiny-slider.css">
       <link href="{{url('/')}}/css/fontawesome.min.css" rel="stylesheet">
       <link href="{{url('/')}}/css/brands.min.css" rel="stylesheet">
       <link href="{{url('/')}}/css/solid.min.css" rel="stylesheet">
       <link href="{{url('/')}}/css/regular.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

    <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet"> --}}

    {{-- <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> --}}


    <style>
        body::-webkit-scrollbar,
    .content-desc::-webkit-scrollbar
        {
            width: 8px;
            height: 8px;
            /*display: none*/
        }
        body::-webkit-scrollbar-thumb,
        .content-desc::-webkit-scrollbar-thumb
        {
            background: #5a83c2;
            border-radius: 4px;
        }
        body::-webkit-scrollbar-thumb:hover,
        .content-desc::-webkit-scrollbar-thumb:hover
        {
            background: #335da8;
            box-shadow: 0 0 2px 1px rgba(0,0,0,0.2);

        }
        body::-webkit-scrollbar-thumb:active,
        .content-desc::-webkit-scrollbar-thumb:active
        {
            background-color: #335da8;
        }
        body::-webkit-scrollbar-track,
        .content-desc::-webkit-scrollbar-track
        {
            background: #e1e1e1;
        }
        body::-webkit-scrollbar-track:hover,
        body::-webkit-scrollbar-track:active,
        .content-desc::-webkit-scrollbar-track:hover,
        .content-desc::-webkit-scrollbar-track:active{
            background: #d4d4d4;
        }

       main {
    min-height: 83vh;
}
    </style>
</head>
<body class="antilaliased font-sans bg-gray-200">

    <div id="app">
        <navigation></navigation>
        <main>
            <toast :show="toast.show" :text="toast.text" :classtoast="toast.classToast" @hide-toast="hideToast"></toast>
            <div style="margin-top:48px">
                <router-view></router-view>
            </div>
            <whatsapp></whatsapp>
        </main>
        <vfooter></vfooter>
    </div>

{{--  <script src="https://www.paypalobjects.com/api/checkout.js"></script>  --}}
    {{--  <script type="text/javascript" src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script type='text/javascript' src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/babel-polyfill/dist/polyfill.min.js"></script>  --}}
     {{--  <script src="https://unpkg.com/@popperjs/core@2"></script>  --}}
       <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
    integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
    crossorigin="anonymous"></script>
    <script src="{{url('/')}}/js/app.js"></script>


</body>
</html>
