
// window.onload = CreateTimer("timer1", 30);


function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

var timers=new Array();
var timerCount=0;
var Timer = new Array();
var TotalSeconds=new Array();

jQuery(document).ready(function(){
   loadTimers();
 });

function loadTimers(){
    for (var i = timers.length - 1; i >= 0; i--) {
        CreateTimer(timers[i]['div_name'],timers[i]['left_time'],i)
    };
}

function CreateTimer(TimerID, Time, i) {
        Timer[i] = document.getElementById(TimerID);
        TotalSeconds[i] = Time;
        
        // UpdateTimer(i);
        Tick(i);
        // window.setTimeout(, 1000);
}

function Tick(i) {
        if (TotalSeconds[i] <= 0) {
            if(timers[i]['next_hop']!=undefined){
                var div_name = timers[i]['next_hop']['div_name'];
                var left_time = timers[i]['next_hop']['left_time'];
                timers[i]['next_hop']=undefined;
                CreateTimer(div_name,left_time,i);
                return;
            }else{
                if(timers[i]['end_message']!=undefined){
                    Timer[i].innerHTML = timers[i]['end_message'];    
                }
                if(timers[i]['end_callback']!=undefined){
                    timers[i]['end_callback']();
                }
            }
            return;
        }

        TotalSeconds[i] -= 1;
        UpdateTimer(i);
        window.setTimeout("Tick("+i+")", 1000);
}

function UpdateTimer(i) {
        var Seconds = TotalSeconds[i];
        
        var Days = Math.floor(Seconds / 86400);
        Seconds -= Days * 86400;

        var Hours = Math.floor(Seconds / 3600);
        Seconds -= Hours * (3600);

        var Minutes = Math.floor(Seconds / 60);
        Seconds -= Minutes * (60);

        var dayNoun=Days==1?" day ":" days ";
        var hourNoun=Hours==1?" hour ":" hours ";
        var minuteNoun=Minutes==1?" minute ":" minutes ";
        var secNoun=Seconds==1?" second ":" seconds ";
        
        var TimeStr = ((Days > 0) ? Days + dayNoun : "") + 
            (Hours>0?LeadingZero(Hours) + hourNoun :"")+
        	+ LeadingZero(Minutes) + minuteNoun + LeadingZero(Seconds) + secNoun;


        Timer[i].innerHTML = TimeStr;
}


function LeadingZero(Time) {

        return (Time < 10) ? "0" + Time : + Time;

}