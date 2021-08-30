require('./bootstrap');
require('alpinejs');
require('./formAlert');
require('@dotcode.moscow/trix-columns');
require('@dotcode.moscow/trix-columns/dist/custom-trix.css');
require('./tabs');

import {Howl, Howler} from 'howler';


Livewire.on('commentAdded', function() {
    var editor = $(".addComment trix-editor");
    editor[0].editor.loadHTML('');
});

var team_id = $('meta[name=team_id]').attr('content');;

Livewire.on('modalsUpdated', function(){
    let $openDrawes = $('.drawer_bg').not(".hidden");
    let $body = $('body');

    if( $openDrawes.length > 0){
        $body.addClass('modal-open');
    }else{
        $body.removeClass('modal-open');
    }
});

var HowlerAudios = [];


$( document ).on( 'click', function( event ) {

    var $target = $(event.target);

    if(  $target.closest('.audio-telephony').length > 0 ){

        let dataSource = $target.closest('.audio-telephony');
        let src = dataSource.data('src');
        let id = dataSource.data('id');

        let playingStoppedId;

        HowlerAudios.forEach(function(cur){
            if( cur.audio.playing() && id === cur.id ){
                playingStoppedId = cur.id;
            }
            cur.audio.stop();
        })

        HowlerAudios = [];

        var sound = new Howl({
            src: [src]
        });

        HowlerAudios.push({
            id: id,
            audio: sound
        });

        if( playingStoppedId == null ){
            sound.play();
        }
    }
});
