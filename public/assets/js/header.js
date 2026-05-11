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


    document.addEventListener('DOMContentLoaded', function() {
        const content = document.querySelector('.promo-ticker-content');
        if (content) {
            const item = content.querySelector('.promo-ticker-item');
            if (item) {
                // Dupliquer le contenu 4 fois pour combler tous les vides
                for (let i = 0; i < 4; i++) {
                    const clone = item.cloneNode(true);
                    content.appendChild(clone);
                }
                
                // Animation JavaScript continue sans interruption
                let offset = 0;
                const itemWidth = item.offsetWidth;
                const speed = 1; // pixels par frame
                
                function animate() {
                    offset += speed;
                    
                    // Quand on a scrollé la moitié, revenir au début instantanément
                    if (offset >= itemWidth) {
                        offset = 0;
                    }
                    
                    content.style.transform = `translateX(-${offset}px)`;
                    requestAnimationFrame(animate);
                }
                
                animate();
            }
        }
    });