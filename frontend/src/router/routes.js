
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    redirect: '/home',
    children: [
      { path: 'home', name: 'Home', component: () => import('pages/Index.vue') },
      { path: 'invoice', name: 'Invoices', component: () => import('pages/InvoiceReport/Invoice.vue') },
      { path: 'invoiceItems', name: 'Invoice Items', component: () => import('pages/InvoiceItems/InvoiceItems.vue') }
    ]
  }
]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
