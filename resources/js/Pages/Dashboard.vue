<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { CCard, Header2, PageContainer } from '@/Components';
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <Header2>Dashboard</Header2>
    </template>

    <PageContainer>
      <CCard title="Image">
        <img
          src="/api/v1/blue-harvest?input=https://images.unsplash.com/photo-1713202294837-abbef9399ae4?q=80&w=1965&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
        />
      </CCard>
    </PageContainer>
  </AuthenticatedLayout>
</template>
