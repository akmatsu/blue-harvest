<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
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

const toast = useToasts();

onMounted(() => {
  if (page.props.auth?.user?.id) {
    window.Echo.private(
      `App.Models.User.${page.props.auth.user.id}`,
    ).notification((notification: broadcastNotification) => {
      if (Array.isArray(notifications.value))
        notifications.value.push(notification);
      else notifications.value = [notification];
    });
  }

  axios
    .get<
      Notification<ImageProcessedNotificationData>[]
    >('/notifications/unread')
    .then((response) => {
      notifications.value =
        response.data?.map((n) => {
          const val: broadcastNotification = {
            image_id: n.data.image_id,
            id: n.id,
            message: n.data.message,
            type: n.type,
          };

          return val;
        }) || [];
    });
});

async function markAsRead(id: string | number, imageId: number) {
  try {
    await axios.post(route('notifications.read', { id }), {}).then((res) => {
      notifications.value = res.data;
    });

    router.get(route('images.manageImage', { id: imageId }));
  } catch (err) {
    toast.error('Something went wrong. Please try again later.');
  }
}
</script>

<template>
  <v-menu>
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
        <v-btn>Dismiss All</v-btn>
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
            markAsRead(notification.id, notification.image_id)
          "
        ></v-list-item>
      </v-list>
    </v-card>
  </v-menu>
</template>
