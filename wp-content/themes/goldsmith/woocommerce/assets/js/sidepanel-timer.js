jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    function CountDownTimer(duration, granularity) {
        this.duration    = duration;
        this.granularity = granularity || 1000;
        this.tickFtns    = [];
        this.running     = false;
    }

    CountDownTimer.prototype.start = function() {
        if (this.running) {
            return;
        }
        this.running = true;
        var start = Date.now(),
        that = this,
        diff, obj;

        (function timer() {
            diff = that.duration - (((Date.now() - start) / 1000) | 0);

            if (diff > 0) {
                setTimeout(timer, that.granularity);
            } else {
                diff = 0;
                that.running = false;
            }

            obj = CountDownTimer.parse(diff);
            that.tickFtns.forEach(function(ftn) {
                ftn.call(this, obj.minutes, obj.seconds);
            }, that);
        }());
    };

    CountDownTimer.prototype.onTick = function(ftn) {
        if (typeof ftn === 'function') {
            this.tickFtns.push(ftn);
        }
        return this;
    };

    CountDownTimer.prototype.expired = function() {
        return !this.running;
    };

    CountDownTimer.parse = function(seconds) {
        return {
            'minutes': (seconds / 60) | 0,
            'seconds': (seconds % 60) | 0
        };
    };

    window.onload = function() {
        $('.goldsmith-cart-timer').each(function(){
            var display = $(this),
                time    = display.data('time'),
                timer   = new CountDownTimer(time);

            timer.onTick(format).onTick(restart).start();

            function restart() {
                if (this.expired()) {
                    setTimeout(function() { timer.start(); }, 1000);
                }
            }

            function format(minutes, seconds) {
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                display.html( minutes + 'm : ' + seconds + 's');
            }
        });
    }

});
