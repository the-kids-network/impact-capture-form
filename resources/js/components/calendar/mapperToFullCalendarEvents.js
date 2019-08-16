// private functions
export default function buildFullCalendarEvents(usertype, events) {
    let mentorLeaves = events.mentor_leaves.map(e => {
        const mentorTooltip = "My leave" + 
            (e.description != null ? "<br/>Description: " + e.description : "")

        const tknTooltip = "Mentor leave: " + e.mentor + 
            (e.description != null ? "<br/>Description: " + e.description : "")

        return {
            id: 'ml-' + e.id,
            start: e.start_date,
            end: e.end_date,
            allDay: true,
            title: (usertype == 'mentor') ? 'Leave' : e.mentor + ' - Leave',
            tooltip: (usertype == 'mentor') ? mentorTooltip : tknTooltip,
            url: '/mentor/leave/' + e.id,
            classNames: ['mentor-leave-event']
        }
    });

    let menteeLeaves = events.mentee_leaves.map(e => {
        const mentorTooltip = "Mentee leave: " + e.mentee + 
            (e.description != null ? "<br/>Description: " + e.description : "")

        const tknTooltip = "Mentee leave: " + e.mentee + 
            (e.description != null ? "<br/>Description: " + e.description : "") + 
            (e.mentor != null ? "<br/>Mentored by: " + e.mentor : "")

        return {
            id: 'ml-' + e.id,
            start: e.start_date,
            end: e.end_date,
            allDay: true,
            title:  e.mentee + ' - Leave',
            tooltip: (usertype == 'mentor') ? mentorTooltip : tknTooltip,
            url: '/mentee/leave/' + e.id,
            classNames: ['mentee-leave-event']
        }
    });

    let plannedSessions = events.planned_sessions.map(e => {
        const mentorTooltip = "Planned session with " + e.mentee

        const tknTooltip = "Planned session between " + e.mentor + " and " + e.mentee

        return {
            id: 'ps-' + e.id,
            start: e.start_date,
            end: e.end_date,
            allDay: true,
            title: (usertype === 'mentor') ? e.mentee : e.mentor + ' - ' + e.mentee,
            tooltip: (usertype == 'mentor') ? mentorTooltip : tknTooltip,
            url: '/planned-session/' + e.id,
            classNames: ['planned-session-event']
        }
    });

    return mentorLeaves.concat(menteeLeaves).concat(plannedSessions);
}