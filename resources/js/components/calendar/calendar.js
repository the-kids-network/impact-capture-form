import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import enLocale from '@fullcalendar/core/locales/en-gb';
import buildFullCalendarEvents from './mapperToFullCalendarEvents'

const STORED_VIEW_TYPE_KEY = "calendarViewType";
const STORED_VIEW_DATE_KEY = "calendarLastViewedDate";

const Component = {

    components: {
        FullCalendar
    },

    template: `<FullCalendar 
                    ref="fullCalendar"
                    :locale="locale"
                    :plugins="plugins" 
                    :header="header"
                    :defaultView="defaultView" 
                    :eventLimit="eventLimit"
                    :views="views"
                    :defaultDate="defaultDate"
                    :events="allEvents"
                    :eventRender="eventRender" />`,
   
    props: ['usertype','events'],

    data() {
        return {
            locale: enLocale,
            plugins: [ dayGridPlugin ],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridDay,dayGridWeek,dayGridMonth'
            },
            defaultView: (this.$ls.get(STORED_VIEW_TYPE_KEY) != null) 
                                ?  this.$ls.get(STORED_VIEW_TYPE_KEY)
                                : 'dayGridMonth',
            eventLimit: true,
            views: {
                dayGridMonth: {
                    eventLimit: 6 
                }
            },
            defaultDate: (this.$session.exists(STORED_VIEW_DATE_KEY))
                                ? new Date(this.$session.get(STORED_VIEW_DATE_KEY))
                                : undefined,
                                
            allEvents: buildFullCalendarEvents(this.usertype, this.events),

            eventRender: function(info) {
                $(info.el).tooltip({ 
                    title: info.event.extendedProps.tooltip,
                    placement: "top",
                    trigger: "hover",
                    container: "body",
                    html: true
                });
            }
        }
    },

    mounted() {
        const calendarApi = this.$refs.fullCalendar.getApi()

        // Add handler for saving default view changes
        let localStorage = this.$ls
        calendarApi.on('viewSkeletonRender', function(info) {
            localStorage.set(STORED_VIEW_TYPE_KEY, info.view.type) 
        });

        // Add handler for saving current date to restore in current session
        let sessionStorage = this.$session
        calendarApi.on('datesRender', function(info) {
            sessionStorage.set(STORED_VIEW_DATE_KEY, calendarApi.getDate())
        });
    },

    methods: {

    },
};


export default Component;
