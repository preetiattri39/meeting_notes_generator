import './bootstrap';

window.meetingRealtime = {
    subscribe(meetingId, callback) {
        if (! window.Echo || ! meetingId) {
            return null;
        }

        const channel = window.Echo.private(`meeting.${meetingId}`);
        channel.listen('.meeting.processing.updated', callback);

        return channel;
    },
};
