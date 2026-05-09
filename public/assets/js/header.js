// header.js — contact phone ringing behavior
document.addEventListener('DOMContentLoaded', function(){
    var contactBtn = document.getElementById('contactBtn');
    var promoLine = document.querySelector('.has-promo-header .announcement-line');
    if (!contactBtn && !promoLine) return;

    var audioCtx = null;
    var oscillators = [];
    var ringing = false;
    var tickerId = null;

    function startRinging(){
        if (ringing) return;
        ringing = true;
        try {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            // pattern: 3 short rings
            var now = audioCtx.currentTime;
            var schedule = [0, 0.5, 1.0];
            schedule.forEach(function(offset){
                var osc = audioCtx.createOscillator();
                var gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(900, now + offset);
                gain.gain.setValueAtTime(0, now + offset);
                gain.gain.linearRampToValueAtTime(0.6, now + offset + 0.02);
                gain.gain.linearRampToValueAtTime(0, now + offset + 0.35);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(now + offset);
                osc.stop(now + offset + 0.4);
                oscillators.push(osc);
            });
            // stop ringing visuals after 3s
            setTimeout(stopRinging, 3200);
        } catch (e){
            // audio not available
            setTimeout(stopRinging, 3200);
        }
    }

    function stopRinging(){
        if (!ringing) return;
        ringing = false;
        if (audioCtx){
            try { audioCtx.close(); } catch(e){}
            audioCtx = null;
        }
        oscillators = [];
    }

});
