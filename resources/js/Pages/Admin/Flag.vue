<script lang="ts" setup>
import { AdminLayout } from '@/Layouts';
import { Flag, Restriction } from '@/types';
import { formatDate } from '@/utils';
import DismissDialog from './components/flag/DismissDialog.vue';
import RestrictDialog from './components/flag/RestrictDialog.vue';
import DeleteDialog from './components/flag/DeleteDialog.vue';

const props = defineProps<{
  flag: Flag;
  restrictions: Restriction[];
}>();
</script>
<template>
  <AdminLayout>
    <v-card title="Review Flagged Item">
      <v-card-text>
        <p>Date Reported: {{ formatDate(flag.created_at) }}</p>
        <p>Type: {{ flag.flaggable_type }}</p>
        <p>Reported by: {{ flag.user_id }}</p>
        <p>Reason: {{ flag.reason }}</p>

        <v-card-actions>
          <DismissDialog :flag-id="flag.id" />
          <RestrictDialog
            :flag-id="flag.id"
            :restrictions
            :image="flag.flaggable"
          />
          <DeleteDialog :flag-id="flag.id" />
        </v-card-actions>
        <div
          v-if="flag.flaggable_type.includes('Image') && flag.flaggable"
          class="d-flex justify-center"
        >
          <v-img :src="flag.flaggable?.url"></v-img>
        </div>
      </v-card-text>
    </v-card>
  </AdminLayout>
</template>
