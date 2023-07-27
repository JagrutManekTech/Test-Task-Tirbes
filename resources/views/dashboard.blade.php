<x-app-layout>
    <!--    <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>-->

    <div class="flex cejl6 cix4s" style="height: calc(100vh - 65px);">
        <div class="flex caro7 cukpw calrm cic9p cdy6k scroll-div" x-ref="contentarea">

            <header class="bg-white border-slate-200 dark:border-slate-700 cma9g cqyqv cjxzq cjgij cqjq5">
                <div class="c2ksj cxrsk cn2cr">
                    <div class="flex items-center cc2cs cpjdt cwdma">

                        <!-- Header: Left side -->
                        <div class="flex">
                             
                        </div>


                    </div>
                </div>
            </header>
            <main class="cirqi">

                <div class="flex calrm messages-main">

                    <!-- Messages sidebar -->
                    <div id="messages-sidebar" class="ctz4m cztd4 ci0xl cl6tz chzrn cw5sv c0zof c5x1m cwxu7 cdr4n chs7d c96ud cjgij cep39 -clu61" :class="msgSidebarOpen ? 'translate-x-0' : '-clu61'">
                        <div class="bg-white border-slate-200 dark:border-slate-700 cu5uh crfg5 caro7 cukpw cj1np cz1vo cnqdg cbacq crb31 cjxzq cho2p">

                            <!-- #users group -->
                            <div>  
                                <div class="c7q80 cpnas"> 
                                    <div class="cloy2">
                                        <div class="camw1 c4cuk ca8f5 clvip cev1n cm3dd">Select user</div>
                                        <ul class="c86fi">
                                            @if($users->count())
                                                @foreach($users as $user)
                                                <li class="cc91t user-chat-btn" data-uid="{{$user->id}}" data-sid="{{\Auth::user()->id}}">
                                                    <button class="flex items-center rounded cc2cs c96ud cj7ph" @click="msgSidebarOpen = false; $refs.contentarea.scrollTop = 99999999;">
                                                        <div class="flex items-center rounded cc2cs c96ud cj7ph"> 
                                                            <div class="chd3l">
                                                                <span class="text-sm text-slate-800 dark:text-slate-100 cz4nn">{{$user->name}}</span>
                                                            </div>
                                                            <div class="flex items-center c4j6o">
                                                                <div class="inline-flex rounded-full c12hv cd4ca cz4nn c7qs0 ci67q cev1n czv4r count_{{$user->id}}">{{App\Helpers\Helper::getUnreadmsgcount($user->id)}}</div>
                                                            </div>
                                                        </div> 
                                                    </button>
                                                </li>
                                                @endforeach
                                            @else
                                            <div class="chd3l">
                                                <span class="text-sm text-slate-800 dark:text-slate-100 cz4nn">No user Found</span>
                                            </div>
                                            @endif
                                        </ul>
                                    </div> 
                                </div>
                            </div> 
                        </div>
                    </div> 
                    <!-- Messages body -->
                    <div class="flex ctz4m cztd4 cne26 cw5sv cic9p cirqi translate-x-0" :class="msgSidebarOpen ? 'translate-x-1/3' : 'translate-x-0'">


                        <!-- Body -->
                        <div class="c2ksj ca1fu cirqi cn2cr cm12e" id="messageWrapper"> 
                            <!-- Chat msg -->

                            <!-- Chat msg --> 
                        </div>

                        <!-- Footer -->
                        <div class="cdr4n cjxzq">
                            <div class="flex items-center bg-white border-slate-200 dark:border-slate-700 crfg5 cc2cs cannk c2ksj ca1fu cwdma cn2cr">

                                <form class="flex cirqi">
                                    <div class="c5huv cirqi">
                                        <label for="message-input" class="czsjw">Type a message</label>
                                        <input id="message-input" class="dark:bg-slate-800 cqqk1 ctk7b c54iw cpxun cqo40 c4ceu c04hc c96ud" type="text" placeholder="Aa">
                                    </div>
                                    <button type="button" class="btn c5e6b c6dbl cdsge c7qs0 send-btn">Send -&gt;</button>
                                </form>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </main> 
        </div>  
    </div> 
</x-app-layout>
<script>
    let $chatInput = $("#message-input");
    let $messageWrapper = $("#messageWrapper");
    let receiver = "";
    let sender = "";
    $('#message-input').keypress(function(e) {
        if (e.keyCode == 13) {  // detect the enter key
            $('.send-btn').click(); // fire a sample click,  you can do anything
            e.preventDefault();
        }        
    });
    $(document).on('click', '.delete-chat', function (e) {
        if(confirm('Ara you sure?')){
            let url = "delete-message";
            let form = $(this);
            let formData = new FormData();
            let token = "{{ csrf_token() }}";
            let message_id = $(this).data('id');
            formData.append('message_id', message_id);
            formData.append('_token', token);
             
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function (response) {
                    $('.data-chat-'+message_id).remove();
                }
            });
        }
    });
    $(document).on('click', '.send-btn', function (e) {
        let message = $chatInput.val();
        sendMessage(message);
        return false;
    });
    function sendMessage(message) {
        if ($chatInput.val() !== '' && receiver != '') {
            $chatInput.val("");
            
            let url = "send-message";
            let form = $(this);
            let formData = new FormData();
            let token = "{{ csrf_token() }}";

            formData.append('message', message);
            formData.append('_token', token);
            formData.append('receiver', receiver);
            formData.append('sender', 2);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function (response) {
                    appendMessageToSender({message: message,id : response.data.id});
                }
            });
        }
    }
    function appendMessageToSender(message) {

        var date = new Date();

        var serverTime = date.getTimezoneOffset();
        var finalTime = new Date(date.getTime() + serverTime * 60 * 1000);
        // console.log(finalTime.getHours()+':'+finalTime.getMinutes()+':'+finalTime.getSeconds());

        var hours = finalTime.getHours();
        var minutes = finalTime.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;


        let name = '{{auth()->user()->name}}';
        let image = '';
 
        let userInfod = '<p class="inline-flex rounded-full c12hv cd4ca cz4nn c7qs0 ci67q cev3n czv4r mr-4">'+name.charAt(0).toUpperCase()+'</p>';
        let messageContentinfo = '<div class="text-sm border shadow-md cpxun cbg5x cdsge c6j37 c7qs0 ca5ph co6nx">' + message.message + '</div>';
        let deleteinfo ='<div class="h-3 cb1p4 c7hxs cz1vo cpvja czxms delete-chat" data-id='+message.id+' ><i class="material-icons">delete</i></div>';
        let dateContentinfo = '<div class="flex items-center cc2cs"><div class="text-slate-500 cz4nn cev1n">' + (message.created_at ? timeFormat(message.created_at) : strTime) + '</div>'+deleteinfo+'</div>';

        let newMessage = '<div data-message="' + (message.id ? message.id : "") + '" class="flex cvtpv cai4l cjc22 data-chat-'+message.id+'">'
                + userInfod + '<div>' + messageContentinfo + dateContentinfo + '</div>' +
                '</div>';

        $messageWrapper.append(newMessage);
        $(".scroll-div").animate({scrollTop: $('.scroll-div').prop("scrollHeight")}, 1000);
    }
    function appendMessageToReceiver(message) {
        let name = $('.chat-header .chat-name span').html();
        let image = $('.chat-header .chat-image').html();
        let userInfod = '<p class="inline-flex rounded-full c12hv cd4ca cz4nn c7qs0 ci67q cev3n czv4r mr-4">'+message.sender.name.charAt(0).toUpperCase()+'</p>';

        let messageContentinfo = '<div class="text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 shadow-md cbg5x c6j37 ca5ph co6nx">' + message.message + '</div>';
         
        let dateContentinfo = '<div class="flex items-center cc2cs"><div class="text-slate-500 cz4nn cev1n">' + (message.created_at ? timeFormat(message.created_at) : strTime) + '</div></div>';

        let newMessage = '<div data-message="" class="flex cvtpv cai4l cjc22">'
                + userInfod + '<div>' + messageContentinfo + dateContentinfo + '</div>' +
                '</div>';

        $messageWrapper.append(newMessage);
        $('.scroll-div')[0].scrollTop = $('.scroll-div')[0].scrollHeight;
    }
//        loadChat(2);
    function loadChat(rec) {

        $('.chat-loaded').show();
        $('#messageWrapper').html('');
        let url = "load-previous-conversation";
        $.ajax({

            url: url,
            type: 'POST',
            data: {_token: '{{ csrf_token() }}', 'receiver': rec},
            dataType: 'JSON',
            success: function (response) {
                $('.chat-loaded').hide(); 
                $.each(response.data.messages, function (index, value) {
                    if (value.sender_id == sender) {
                        appendMessageToSender(value);
                    } else {
                        appendMessageToReceiver(value);
                    }
                });
                $('.scroll-div')[0].scrollTop = $('.scroll-div')[0].scrollHeight;
            }
        });
    }
    function timeFormat(datetime) {
        return moment(datetime, 'YYYY-MM-DD HH:mm:ss').format('h:mm A');
    }

    $(document).on('click', '.user-chat-btn', function (e) { 
        e.preventDefault(); 
        receiver = $(this).data('uid');
        sender = $(this).data('sid'); 
        loadChat(receiver); 
        $('.user-chat-btn button').removeClass('cgq6l');
        $(this).children('button').addClass('cgq6l');
 
    });

</script>
