
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

window.onload = function(){
	for (var i = timers.length - 1; i >= 0; i--) {
		CreateTimer(timers[i][0],timers[i][1],i)
	};
};

function CreateTimer(TimerID, Time, i) {
        Timer[i] = document.getElementById(TimerID);
        TotalSeconds[i] = Time;
        
        UpdateTimer(i);
        window.setTimeout("Tick("+i+")", 1000);
}

function Tick(i) {
        if (TotalSeconds[i] <= 0) {
                alert("Time's up!")
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
        
        var TimeStr = ((Days > 0) ? Days + dayNoun : "") + LeadingZero(Hours) + hourNoun 
        	+ LeadingZero(Minutes) + minuteNoun + LeadingZero(Seconds) + secNoun;


        Timer[i].innerHTML = TimeStr;
}


function LeadingZero(Time) {

        return (Time < 10) ? "0" + Time : + Time;

}