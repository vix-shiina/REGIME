// header.js — contact phone ringing behavior
document.addEventListener('DOMContentLoaded', function(){
    var contactBtn = document.getElementById('contactBtn');
    var ringDot = document.getElementById('ringDot');
    if (!contactBtn) return;

    var audioCtx = null;
    var oscillators = [];
    var ringing = false;

    function startRinging(){
        if (ringing) return;
        ringing = true;
        ringDot.style.opacity = '1';
        ringDot.classList.add('ringing');
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
        ringDot.classList.remove('ringing');
        ringDot.style.opacity = '0';
        if (audioCtx){
            try { audioCtx.close(); } catch(e){}
            audioCtx = null;
        }
        oscillators = [];
    }

    contactBtn.addEventListener('click', function(e){
        e.preventDefault();
        startRinging();
    });
});
