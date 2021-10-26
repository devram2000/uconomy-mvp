// // import { Calendar } from '@fullcalendar/core';
// // import dayGridPlugin from '@fullcalendar/daygrid';
// // import timeGridPlugin from '@fullcalendar/timegrid';
// // import listPlugin from '@fullcalendar/list';

// // document.addEventListener('DOMContentLoaded', function() {
// //     const calendarEl = document.getElementById("calendar");

// //     let calendar = new Calendar(calendarEl, {
// //         locale: 'en',
// //         plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
// //         initialView: 'dayGridMonth',
// //         headerToolbar: {
// //             left: 'prev,next today',
// //             center: 'title',
// //             right: 'dayGridMonth,dayGridWeek,listWeek'
// //         },
// //         // go ahead with other parameters
// //         eventRender: function (event, element, view) {
// //             if (event.allDay === 'true') {
// //                     event.allDay = true;
// //             } else {
// //                     event.allDay = false;
// //             }
// //         },
// //         selectable: true,
// //         selectHelper: true,
// //         select: function (start, end, allDay) {
// //             var title = prompt('Event Title:');
// //             if (title) {
// //                 var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
// //                 var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
// //                 $.ajax({
// //                     url: SITEURL + "/upayAjax",
// //                     data: {
// //                         title: title,
// //                         start: start,
// //                         end: end,
// //                         type: 'add'
// //                     },
// //                     type: "POST",
// //                     success: function (data) {
// //                         displayMessage("Event Created Successfully");

// //                         calendar.fullCalendar('renderEvent',
// //                             {
// //                                 id: data.id,
// //                                 title: title,
// //                                 start: start,
// //                                 end: end,
// //                                 allDay: allDay
// //                             },true);

// //                         calendar.fullCalendar('unselect');
// //                     }
// //                 });
// //             }
// //         },
// //         eventDrop: function (event, delta) {
// //             var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
// //             var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

// //             $.ajax({
// //                 url: SITEURL + '/upayAjax',
// //                 data: {
// //                     title: event.title,
// //                     start: start,
// //                     end: end,
// //                     id: event.id,
// //                     type: 'update'
// //                 },
// //                 type: "POST",
// //                 success: function (response) {
// //                     displayMessage("Event Updated Successfully");
// //                 }
// //             });
// //         },
// //         eventClick: function (event) {
// //             var deleteMsg = confirm("Do you really want to delete?");
// //             if (deleteMsg) {
// //                 $.ajax({
// //                     type: "POST",
// //                     url: SITEURL + '/upayAjax',
// //                     data: {
// //                             id: event.id,
// //                             type: 'delete'
// //                     },
// //                     success: function (response) {
// //                         calendar.fullCalendar('removeEvents', event.id);
// //                         displayMessage("Event Deleted Successfully");
// //                     }
// //                 });
// //             }
// //         }

// //     });

// //     calendar.render();
// // });



// $(document).ready(function () {
                           
//     var SITEURL = "{{ url('/') }}";
      
//     $.ajaxSetup({
//         headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });
      
//     var calendar = $('#calendar').fullCalendar({
//                         editable: true,
//                         events: SITEURL + "/upay",
//                         displayEventTime: false,
//                         editable: true,
//                         eventRender: function (event, element, view) {
//                             if (event.allDay === 'true') {
//                                     event.allDay = true;
//                             } else {
//                                     event.allDay = false;
//                             }
//                         },
//                         selectable: true,
//                         selectHelper: true,
//                         select: function (start, end, allDay) {
//                             var title = prompt('Event Title:');
//                             if (title) {
//                                 var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
//                                 var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
//                                 $.ajax({
//                                     url: SITEURL + "/upayAjax",
//                                     data: {
//                                         title: title,
//                                         start: start,
//                                         end: end,
//                                         type: 'add'
//                                     },
//                                     type: "POST",
//                                     success: function (data) {
//                                         displayMessage("Event Created Successfully");
      
//                                         calendar.fullCalendar('renderEvent',
//                                             {
//                                                 id: data.id,
//                                                 title: title,
//                                                 start: start,
//                                                 end: end,
//                                                 allDay: allDay
//                                             },true);
      
//                                         calendar.fullCalendar('unselect');
//                                     }
//                                 });
//                             }
//                         },
//                         eventDrop: function (event, delta) {
//                             var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
//                             var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
      
//                             $.ajax({
//                                 url: SITEURL + '/upayAjax',
//                                 data: {
//                                     title: event.title,
//                                     start: start,
//                                     end: end,
//                                     id: event.id,
//                                     type: 'update'
//                                 },
//                                 type: "POST",
//                                 success: function (response) {
//                                     displayMessage("Event Updated Successfully");
//                                 }
//                             });
//                         },
//                         eventClick: function (event) {
//                             var deleteMsg = confirm("Do you really want to delete?");
//                             if (deleteMsg) {
//                                 $.ajax({
//                                     type: "POST",
//                                     url: SITEURL + '/upayAjax',
//                                     data: {
//                                             id: event.id,
//                                             type: 'delete'
//                                     },
//                                     success: function (response) {
//                                         calendar.fullCalendar('removeEvents', event.id);
//                                         displayMessage("Event Deleted Successfully");
//                                     }
//                                 });
//                             }
//                         }
     
//                     });
     
//     });
     
//     function displayMessage(message) {
//         toastr.success(message, 'Event');
//     } 
