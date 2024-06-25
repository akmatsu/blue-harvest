<script lang="ts" setup>
import { useRequest } from '@/composables';
import { ImageProcessedNotificationData, Notification } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();

type broadcastNotification = {
  image_id: number;
  message: string;
  id: string;
  type: string;
};

const notifications = ref<broadcastNotification[]>();

onMounted(() => {
  if (page.props.auth?.user?.id) {
    connectToWS();
    getNotifications.exec();
  }
});

function connectToWS() {
  window.Echo.private(
    `App.Models.User.${page.props.auth.user.id}`,
  ).notification((notification: broadcastNotification) => {
    if (Array.isArray(notifications.value))
      notifications.value.push(notification);
    else notifications.value = [notification];
  });
}

const getNotifications = reactive(
  useRequest(async () => {
    const res = await axios.get<Notification<ImageProcessedNotificationData>[]>(
      '/notifications/unread',
    );

    if (res.data) {
      notifications.value = res.data?.map((n) => {
        return formatNotification(n);
      });
    }
  }),
);

const markAsRead = reactive(
  useRequest(async (id: string | number, imageId: number) => {
    await axios.post(route('notifications.read', { id }), {}).then((res) => {
      notifications.value = res.data;
    });

    router.get(route('images.manageImage', { id: imageId }));
  }),
);

const markAllAsRead = reactive(
  useRequest(async () => {
    await axios.post<Notification<ImageProcessedNotificationData>[]>(
      route('notifications.read.all'),
    );

    notifications.value = [];
  }),
);

function formatNotification(
  n: Notification<ImageProcessedNotificationData>,
): broadcastNotification {
  return {
    image_id: n.data.image_id,
    id: n.id,
    message: n.data.message,
    type: n.type,
  };
}
</script>

<template>
  <v-menu :close-on-content-click="false">
    <template #activator="{ props }">
      <v-btn size="x-small" icon v-bind="props">
        <v-badge dot v="if=" color="red" v-if="notifications?.length">
          <v-icon icon="mdi-bell" size="20"></v-icon>
        </v-badge>
        <v-icon v-else icon="mdi-bell" size="20"></v-icon>
      </v-btn>
    </template>
    <v-card>
      <v-card-actions>
        <v-btn
          @click="markAllAsRead.exec"
          :disabled="!notifications?.length"
          :loading="markAllAsRead.loading"
        >
          Dismiss All
        </v-btn>
      </v-card-actions>
      <v-list>
        <v-list-item
          link
          v-for="notification in notifications"
          :key="notification.id"
          :title="notification.message"
          prepend-icon="mdi-check"
          :href="route('images.view', { id: notification.image_id })"
          @click.prevent.stop="
            markAsRead.exec(notification.id, notification.image_id)
          "
        ></v-list-item>
      </v-list>
    </v-card>
  </v-menu>
</template>
