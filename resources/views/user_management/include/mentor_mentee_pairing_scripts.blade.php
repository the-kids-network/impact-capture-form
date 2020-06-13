<script type="text/javascript">
    $('#assignMenteeToMentorForm').on('submit', function() {
        const mentorId = $('#assignMenteeToMentorForm #mentorSelect').val()
        const menteeId = $('#assignMenteeToMentorForm #menteeSelect').val()
        $('#assignMenteeToMentorForm').attr('action', '/users/'+ mentorId +'/mentees/'+menteeId)
    });
</script>
