<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='fullcalendar/lib/main.css' rel='stylesheet' />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src='fullcalendar/lib/main.js'></script>
<script> 
let calendar;
let maxConcurrentEvents=3;
let maxEventsInInterval =  maxConcurrentEvents
document.addEventListener("DOMContentLoaded", function() {
let calendarEl=document.getElementById("calendar");    
let course=location.hash.split("#")[1]

calendar = new FullCalendar.Calendar(calendarEl, {
  initialView: 'timeGridWeek',
  businessHours: true,
 // eventMaxStack:3,
  selectConstraint:"businessHours",
  // editable: true,
  selectOverlap:true,
      selectable: true,
      selectMirror: true,
      slotDuration: '00:30:00',
      dateClick(arg)
      {
      // console.log(arg)   
      },
      select(arg) {
        let concurrentEvents= getEventsByTime(arg.start,arg.end ).length
        console.log(concurrentEvents,maxConcurrentEvents)
        if(checkForMax(arg.start,arg.stop,concurrentEvents)){
          alert("Maximum number of reservations already made for this time, please pick a different day or time")
          return false;
        }
        let title = confirm(
          'Register a computer from ' + arg.startStr + ' to ' + arg.endStr + ' (excl).\n' +
          'Enter a title:'
        )

        if (title || arg.view.type.match(/^timeGrid/)) { // kill the mirror
          calendar.unselect()
        }

        if (title) {
          calendar.addEvent({
            title,
            start: arg.start,
            end: arg.end
          })
         
        }
      }



});
calendar.render();

});

function checkForMax(start,end,concurrentEvents){
  console.log( 'Events :' + getEventsByTime( start, end ).length );
    var ev = getEventsByTime( start, end );
    var itms = {};            
    let isMax=false;
    ev.forEach(function(entry){

      var begin = moment(entry.start);
      var final = moment(entry.end);
 
      console.log(begin.diff(final))

    while( begin.diff(final) < 0 ) {
        itms[begin] =  ( itms[begin] || 0) + 1;
        if( itms[begin] >= maxEventsInInterval ) {
          isMax=true;
        }

        begin = moment(begin).add(900,'seconds');
    }

});
return isMax;


}

function getEventsByTime( start, stop ) {
  let todaysEvents=calendar.getEvents().filter(function(event) {
console.log(event.start,start, event.start >= start)
       return ( 
           ( event.start >= start && event.start <= stop ) 
    
       );
    });
    return todaysEvents;
}

</script>
    <title>Document</title>
</head>
<body>
    <div id="calendar"></div>
</body>
</html>