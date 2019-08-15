// private functions
export default function buildFullCalendarEvents(usertype, events) {
    let leaves = events.mentors_leaves.map(e => {
        const title = (usertype == 'mentor') ? 'Leave' : e.mentor + ' - Leave'
        const tooltip = (e.description != null) ? title + ' - ' + e.description : title

        return {
            id: 'ml-' + e.id,
            start: e.start_date,
            end: e.end_date,
            allDay: true,
            title: title,
            tooltip: tooltip,
            url: '/mentor/leave/' + e.id,
            classNames: ['ml-event']
        }
    });

    let plannedSessions = events.planned_sessions.map(e => {
        const title = (usertype === 'mentor') ? e.mentee : e.mentor + ' - ' + e.mentee
        const tooltip = (e.location != null) ? title + ' - ' + e.location : title

        return {
            id: 'ps-' + e.id,
            start: e.start_date,
            end: e.end_date,
            allDay: true,
            title: title,
            tooltip: tooltip,
            url: '/planned-session/' + e.id,
            classNames: ['ps-event']
        }
    });

    console.log(plannedSessions)

    return leaves.concat(plannedSessions);
}