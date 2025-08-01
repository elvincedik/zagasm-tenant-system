import Vue from "vue";
import store from "./store";
import NProgress from "nprogress";
import Router from "vue-router";
Vue.use(Router);



// create new router

const baseRoutes = [
    // {
    //     path: '/register',
    //     name: 'register',
    //     // component: () => import('./views/Register.vue')
    //     component: () =>
    //         import(
    //             /* webpackChunkName: "dashboard" */ "./views/Register"
    //         )
    // },
    {
        path: "/",
        component: () => import("./views/app"),
        // redirect: "/app/dashboard",
        name: 'app',

        children: [
            {
                path: "/app/dashboard",
                name: "dashboard",
                component: () =>
                    import(
                        /* webpackChunkName: "dashboard" */ "./views/app/dashboard/dashboard"
                    )

            },

            //Products
            {
                path: "/app/products",
                component: () =>
                    import(
                        /* webpackChunkName: "products" */ "./views/app/pages/products"
                    ),
                redirect: "app/products/list",
                children: [
                    {
                        name: "index_products",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_products" */ "./views/app/pages/products/index_products"
                            )
                    },
                    {
                        path: "store",
                        name: "store_product",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_product" */ "./views/app/pages/products/Add_product"
                            )
                    },
                    {
                        path: "edit/:id",
                        name: "edit_product",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_product" */ "./views/app/pages/products/Edit_product"
                            )
                    },
                    {
                        path: "detail/:id",
                        name: "detail_product",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_product" */ "./views/app/pages/products/Detail_Product"
                            )
                    },

                    {
                        path: "opening_stock_import",
                        name: "opening_stock_import",
                        component: () =>
                            import(
                                /* webpackChunkName: "opening_stock_import" */ "./views/app/pages/products/opening_stock_import"
                            )
                    },

                    {
                        path: "barcode",
                        name: "barcode",
                        component: () =>
                            import(
                                /* webpackChunkName: "barcode" */ "./views/app/pages/products/barcode"
                            )
                    },

                    {
                        path: "count_stock",
                        name: "count_stock",
                        component: () =>
                            import(
                                /* webpackChunkName: "count_stock" */ "./views/app/pages/products/count_stock"
                            )
                    },
                    // categories
                    {
                        name: "categories",
                        path: "Categories",
                        component: () =>
                            import(
                                /* webpackChunkName: "Categories" */ "./views/app/pages/products/categorie"
                            )
                    },

                    // brands
                    {
                        name: "brands",
                        path: "Brands",
                        component: () =>
                            import(
                                /* webpackChunkName: "Brands" */ "./views/app/pages/products/brands"
                            )
                    },

                    // units
                    {
                        name: "units",
                        path: "Units",
                        component: () =>
                            import(
                                /* webpackChunkName: "units" */ "./views/app/pages/products/units"
                            )
                    },
                ]
            },

            //Adjustement
            {
                path: "/app/adjustments",
                component: () =>
                    import(
                        /* webpackChunkName: "adjustments" */ "./views/app/pages/adjustment"
                    ),
                redirect: "/app/adjustments/list",
                children: [
                    {
                        name: "index_adjustment",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_adjustment" */
                                "./views/app/pages/adjustment/index_Adjustment"
                            )
                    },
                    {
                        name: "store_adjustment",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_adjustment" */
                                "./views/app/pages/adjustment/Create_Adjustment"
                            )
                    },
                    {
                        name: "edit_adjustment",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_adjustment" */
                                "./views/app/pages/adjustment/Edit_Adjustment"
                            )
                    }
                ]
            },

            //Transfer
            {
                path: "/app/transfers",
                component: () =>
                    import(
                        /* webpackChunkName: "transfers" */ "./views/app/pages/transfers"
                    ),
                redirect: "/app/transfers/list",
                children: [
                    {
                        name: "index_transfer",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_transfer" */ "./views/app/pages/transfers/index_transfer"
                            )
                    },
                    {
                        name: "store_transfer",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_transfer" */
                                "./views/app/pages/transfers/create_transfer"
                            )
                    },
                    {
                        name: "edit_transfer",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_transfer" */ "./views/app/pages/transfers/edit_transfer"
                            )
                    }
                ]
            },

            // accounts
            {
                name: "accounts",
                path: "/app/accounts",
                component: () =>
                    import(
                        /* webpackChunkName: "accounts" */ "./views/app/pages/accounts/account_list"
                    )
            },

            //Projects
            {
                path: "/app/projects",
                component: () =>
                    import(
                        /* webpackChunkName: "projects" */ "./views/app/pages/projects"
                    ),
                redirect: "/app/projects/list",
                children: [
                    {
                        name: "index_project",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_project" */
                                "./views/app/pages/projects/index_project"
                            )
                    },
                    {
                        name: "store_project",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_project" */
                                "./views/app/pages/projects/store_project"
                            )
                    },
                    {
                        name: "edit_project",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_project" */
                                "./views/app/pages/projects/Edit_project"
                            )
                    },

                ]
            },

            //Tasks
            {
                path: "/app/tasks",
                component: () =>
                    import(
                        /* webpackChunkName: "tasks" */ "./views/app/pages/tasks"
                    ),
                redirect: "/app/tasks/list",
                children: [
                    {
                        name: "index_task",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_task" */
                                "./views/app/pages/tasks/index_task"
                            )
                    },
                    {
                        name: "store_task",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_task" */
                                "./views/app/pages/tasks/store_task"
                            )
                    },
                    {
                        name: "edit_task",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_task" */
                                "./views/app/pages/tasks/Edit_task"
                            )
                    },

                ]
            },


            // transfer_money
            {
                name: "transfer_money",
                path: "/app/transfer_money",
                component: () =>
                    import(
                        /* webpackChunkName: "transfer_money" */ "./views/app/pages/accounts/transfer_money"
                    )
            },



            //expenses
            {
                path: "/app/expenses",
                component: () =>
                    import(
                        /* webpackChunkName: "expenses" */ "./views/app/pages/expense"
                    ),
                redirect: "/app/expenses/list",
                children: [
                    {
                        name: "index_expense",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_expense" */ "./views/app/pages/expense/index_expense"
                            )
                    },
                    {
                        name: "store_expense",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_expense" */ "./views/app/pages/expense/create_expense"
                            )
                    },
                    {
                        name: "edit_expense",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_expense" */ "./views/app/pages/expense/edit_expense"
                            )
                    },
                    {
                        name: "expense_category",
                        path: "category",
                        component: () =>
                            import(
                                /* webpackChunkName: "expense_category" */ "./views/app/pages/expense/category_expense"
                            )
                    },

                ]
            },

            //deposits
            {
                path: "/app/deposits",
                component: () =>
                    import(
                        /* webpackChunkName: "deposits" */ "./views/app/pages/deposits"
                    ),
                redirect: "/app/deposits/list",
                children: [

                    {
                        name: "index_deposit",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_deposit" */ "./views/app/pages/deposits/index_deposit"
                            )
                    },
                    {
                        name: "store_deposit",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_deposit" */ "./views/app/pages/deposits/create_deposit"
                            )
                    },
                    {
                        name: "edit_deposit",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_deposit" */ "./views/app/pages/deposits/edit_deposit"
                            )
                    },
                    {
                        name: "deposit_category",
                        path: "category",
                        component: () =>
                            import(
                                /* webpackChunkName: "deposit_category" */ "./views/app/pages/deposits/deposit_category"
                            )
                    }
                ]
            },

            //Quotation
            {
                path: "/app/quotations",
                component: () =>
                    import(
                        /* webpackChunkName: "quotations" */ "./views/app/pages/quotations"
                    ),
                redirect: "/app/quotations/list",
                children: [
                    {
                        name: "index_quotation",
                        path: "list",
                        component: () =>
                            import(
                                "./views/app/pages/quotations/index_quotation"
                            )
                    },
                    {
                        name: "store_quotation",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_quotation" */
                                "./views/app/pages/quotations/create_quotation"
                            )
                    },
                    {
                        name: "edit_quotation",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_quotation" */
                                "./views/app/pages/quotations/edit_quotation"
                            )
                    },
                    {
                        name: "detail_quotation",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_quotation" */
                                "./views/app/pages/quotations/detail_quotation"
                            )
                    },
                    {
                        name: "change_to_sale",
                        path: "create_sale/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "change_to_sale" */ "./views/app/pages/sales/change_to_sale.vue"
                            )
                    }
                ]
            },

            //Purchase
            {
                path: "/app/purchases",
                component: () =>
                    import(
                        /* webpackChunkName: "purchases" */ "./views/app/pages/purchases"
                    ),
                redirect: "/app/purchases/list",
                children: [
                    {
                        name: "index_purchases",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_purchases" */ "./views/app/pages/purchases/index_purchase"
                            )
                    },
                    {
                        name: "store_purchase",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_purchase" */
                                "./views/app/pages/purchases/create_purchase"
                            )
                    },

                    {
                        name: "import_purchases",
                        path: "import_purchases",
                        component: () =>
                            import(
                                /* webpackChunkName: "import_purchases" */
                                "./views/app/pages/purchases/import_purchases"
                            )
                    },
                    {
                        name: "edit_purchase",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_purchase" */ "./views/app/pages/purchases/edit_purchase"
                            )
                    },
                    {
                        name: "purchase_return",
                        path: "purchase_return/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "create_purchase_return" */ "./views/app/pages/purchase_return/create_purchase_return"
                            )
                    },
                    {
                        name: "detail_purchase",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_purchase" */
                                "./views/app/pages/purchases/detail_purchase"
                            )
                    }
                ]
            },

            //Sale
            {
                path: "/app/sales",
                component: () =>
                    import(
                        /* webpackChunkName: "sales" */ "./views/app/pages/sales"
                    ),
                redirect: "/app/sales/list",
                children: [
                    {
                        name: "index_sales",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_sales" */ "./views/app/pages/sales/index_sale"
                            )
                    },
                    {
                        name: "store_sale",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "store_sale" */ "./views/app/pages/sales/create_sale"
                            )
                    },
                    {
                        name: "edit_sale",
                        path: "edit/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_sale" */ "./views/app/pages/sales/edit_sale"
                            )
                    },
                    {
                        name: "sale_return",
                        path: "sale_return/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "create_sale_return" */ "./views/app/pages/sale_return/create_sale_return"
                            )
                    },
                    {
                        name: "detail_sale",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_sale" */ "./views/app/pages/sales/detail_sale"
                            )
                    },
                    {
                        name: "shipment",
                        path: "shipment",
                        component: () =>
                            import(
                                /* webpackChunkName: "shipment" */ "./views/app/pages/sales/shipments"
                            )
                    }
                ]
            },

            // Sales Return
            {
                path: "/app/sale_return",
                component: () =>
                    import(
                        /* webpackChunkName: "sale_return" */ "./views/app/pages/sale_return"
                    ),
                redirect: "/app/sale_return/list",
                children: [
                    {
                        name: "index_sale_return",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_sale_return" */
                                "./views/app/pages/sale_return/index_sale_return"
                            )
                    },
                    // {
                    //     name: "store_sale_return",
                    //     path: "store",
                    //     component: () =>
                    //         import(
                    //             /* webpackChunkName: "store_sale_return" */
                    //             "./views/app/pages/sale_return/create_sale_return"
                    //         )
                    // },
                    {
                        name: "edit_sale_return",
                        path: "edit/:id/:sale_id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_sale_return" */
                                "./views/app/pages/sale_return/edit_sale_return"
                            )
                    },
                    {
                        name: "detail_sale_return",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_sale_return" */
                                "./views/app/pages/sale_return/detail_sale_return"
                            )
                    }
                ]
            },

            // purchase Return
            {
                path: "/app/purchase_return",
                component: () =>
                    import(
                        /* webpackChunkName: "purchase_return" */ "./views/app/pages/purchase_return"
                    ),
                redirect: "/app/purchase_return/list",
                children: [
                    {
                        name: "index_purchase_return",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "index_purchase_return" */
                                "./views/app/pages/purchase_return/index_purchase_return"
                            )
                    },
                    // {
                    //     name: "store_purchase_return",
                    //     path: "store",
                    //     component: () =>
                    //         import(
                    //             /* webpackChunkName: "store_purchase_return" */
                    //             "./views/app/pages/purchase_return/create_purchase_return"
                    //         )
                    // },
                    {
                        name: "edit_purchase_return",
                        path: "edit/:id/:purchase_id",
                        component: () =>
                            import(
                                /* webpackChunkName: "edit_purchase_return" */
                                "./views/app/pages/purchase_return/edit_purchase_return"
                            )
                    },
                    {
                        name: "detail_purchase_return",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_purchase_return" */
                                "./views/app/pages/purchase_return/detail_purchase_return"
                            )
                    }
                ]
            },

            // Hrm
            {
                path: "/app/hrm",
                component: () =>
                    import(
                        /* webpackChunkName: "hrm" */ "./views/app/pages/hrm"
                    ),
                redirect: "/app/hrm/employees",
                children: [
                    // employees
                    {
                        path: "employees",
                        component: () =>
                            import(
                                /* webpackChunkName: "employees" */ "./views/app/pages/hrm/employees"
                            ),
                        redirect: "/app/hrm/employees/list",
                        children: [
                            {
                                name: "employees_list",
                                path: "list",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "index_employee" */
                                        "./views/app/pages/hrm/employees/index_employee"
                                    )
                            },
                            {
                                name: "store_employee",
                                path: "store",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "store_employee" */
                                        "./views/app/pages/hrm/employees/employee_create"
                                    )
                            },
                            {
                                name: "edit_employee",
                                path: "edit/:id",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "edit_employee" */
                                        "./views/app/pages/hrm/employees/employee_edit"
                                    )
                            },
                            {
                                name: "detail_employee",
                                path: "detail/:id",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "detail_employee" */
                                        "./views/app/pages/hrm/employees/employee_details"
                                    )
                            },
                        ]
                    },
                    // company
                    {
                        name: "company",
                        path: "company",
                        component: () =>
                            import(/* webpackChunkName: "company" */"./views/app/pages/hrm/company")
                    },

                    // departments
                    {
                        name: "departments",
                        path: "departments",
                        component: () =>
                            import(/* webpackChunkName: "departments" */"./views/app/pages/hrm/department")
                    },

                    // designations
                    {
                        name: "designations",
                        path: "designations",
                        component: () =>
                            import(/* webpackChunkName: "designations" */"./views/app/pages/hrm/designation")
                    },

                    // office_shift
                    {
                        name: "office_shift",
                        path: "office_shift",
                        component: () =>
                            import(/* webpackChunkName: "office_shift" */"./views/app/pages/hrm/office_shift")
                    },

                    // attendance
                    {
                        name: "attendance",
                        path: "attendance",
                        component: () =>
                            import(/* webpackChunkName: "attendance" */"./views/app/pages/hrm/attendance")
                    },

                    // holidays
                    {
                        name: "holidays",
                        path: "holidays",
                        component: () =>
                            import(/* webpackChunkName: "holidays" */"./views/app/pages/hrm/holidays")
                    },

                    // payrolls
                    {
                        name: "payrolls",
                        path: "payrolls",
                        component: () =>
                            import(/* webpackChunkName: "payrolls" */"./views/app/pages/hrm/payrolls")
                    },


                    {
                        path: "leaves",
                        component: () =>
                            import(
                                /* webpackChunkName: "leaves" */ "./views/app/pages/hrm/leaves"
                            ),
                        redirect: "/app/hrm/leaves/list",
                        children: [
                            {
                                name: "leave_list",
                                path: "list",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "leave_list" */
                                        "./views/app/pages/hrm/leaves/leave_list"
                                    )
                            },
                            {
                                name: "leave_type",
                                path: "type",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "leave_type" */
                                        "./views/app/pages/hrm/leaves/leave_type"
                                    )
                            },

                        ]
                    },


                ]
            },

            // People
            {
                path: "/app/People",
                component: () =>
                    import(
                        /* webpackChunkName: "People" */ "./views/app/pages/people"
                    ),
                redirect: "/app/People/Customers",
                children: [
                    // Customers
                    {
                        name: "Customers",
                        path: "Customers",
                        component: () =>
                            import(
                                /* webpackChunkName: "Customers" */ "./views/app/pages/people/customers"
                            )
                    },

                    // Customers
                    {
                        name: "Customers_without_ecommerce",
                        path: "Customers_without_ecommerce",
                        component: () =>
                            import(
                                /* webpackChunkName: "Customers_without_ecommerce" */ "./views/app/pages/people/Customers_without_ecommerce"
                            )
                    },

                    // Suppliers
                    {
                        name: "Suppliers",
                        path: "Suppliers",
                        component: () =>
                            import(
                                /* webpackChunkName: "Suppliers" */ "./views/app/pages/people/providers"
                            )
                    },

                    // Users
                    {
                        name: "user",
                        path: "Users",
                        component: () =>
                            import(
                                /* webpackChunkName: "Users" */ "./views/app/pages/people/users"
                            )
                    }
                ]
            },

            // subscription_product
            {
                path: "/app/subscription_product",
                component: () =>
                    import(
                        /* webpackChunkName: "subscription_product" */ "./views/app/pages/subscription_product"
                    ),
                redirect: "/app/subscription_product/list",
                children: [
                    {
                        name: "subscription_product",
                        path: "list",
                        component: () =>
                            import(
                                /* webpackChunkName: "subscription_product" */
                                "./views/app/pages/subscription_product/subscription_product_list"
                            )
                    },

                    {
                        name: "subscription_product_create",
                        path: "store",
                        component: () =>
                            import(
                                /* webpackChunkName: "subscription_product_create" */
                                "./views/app/pages/subscription_product/subscription_product_create"
                            )
                    },

                    {
                        name: "subscription_product_details",
                        path: "detail/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "subscription_product_details" */
                                "./views/app/pages/subscription_product/subscription_product_details"
                            )
                    },




                ]
            },

            // Settings
            {
                path: "/app/settings",
                component: () =>
                    import(
                        /* webpackChunkName: "settings" */ "./views/app/pages/settings"
                    ),
                redirect: "/app/settings/System_settings",
                children: [
                    // Permissions
                    {
                        path: "permissions",
                        component: () =>
                            import(
                                /* webpackChunkName: "permissions" */ "./views/app/pages/settings/permissions"
                            ),
                        redirect: "/app/settings/permissions/list",
                        children: [
                            {
                                name: "groupPermission",
                                path: "list",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "groupPermission" */
                                        "./views/app/pages/settings/permissions/Permissions"
                                    )
                            },
                            {
                                name: "store_permission",
                                path: "store",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "store_permission" */
                                        "./views/app/pages/settings/permissions/Create_permission"
                                    )
                            },
                            {
                                name: "edit_permission",
                                path: "edit/:id",
                                component: () =>
                                    import(
                                        /* webpackChunkName: "edit_permission" */
                                        "./views/app/pages/settings/permissions/Edit_permission"
                                    )
                            }
                        ]
                    },

                    // payment_methods
                    {
                        name: "payment_methods",
                        path: "payment_methods",
                        component: () =>
                            import(
                                /* webpackChunkName: "payment_methods" */ "./views/app/pages/settings/payment_methods"
                            )
                    },

                    // sms_settings
                    {
                        name: "sms_settings",
                        path: "sms_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "sms_settings" */ "./views/app/pages/settings/sms_settings"
                            )
                    },

                    // sms_templates
                    {
                        name: "sms_templates",
                        path: "sms_templates",
                        component: () =>
                            import(
                                /* webpackChunkName: "sms_templates" */ "./views/app/pages/settings/sms_templates"
                            )
                    },

                    // email_templates
                    {
                        name: "email_templates",
                        path: "email_templates",
                        component: () =>
                            import(
                                /* webpackChunkName: "email_templates" */ "./views/app/pages/settings/email_templates"
                            )
                    },

                    // appearance_settings
                    {
                        name: "appearance_settings",
                        path: "appearance_settings",
                        component: () =>
                            import(
                            /* webpackChunkName: "appearance_settings" */ "./views/app/pages/settings/appearance_settings"
                            )
                    },

                    // translations_settings
                    {
                        name: "translations_settings",
                        path: "translations_settings",
                        component: () =>
                            import(
                            /* webpackChunkName: "translations_settings" */ "./views/app/pages/settings/translations_settings"
                            )
                    },

                    {
                        name: "translations_view",
                        path: "/translations_view/:locale",
                        component: () =>
                            import(
                        /* webpackChunkName: "translations_view" */ "./views/app/pages/settings/translations_view"
                            )
                    },

                    // pos_settings
                    {
                        name: "pos_settings",
                        path: "pos_settings",
                        component: () =>
                            import(
                            /* webpackChunkName: "pos_settings" */ "./views/app/pages/settings/pos_settings"
                            )
                    },

                    // payment_gateway
                    {
                        name: "payment_gateway",
                        path: "payment_gateway",
                        component: () =>
                            import(
                                /* webpackChunkName: "payment_gateway" */ "./views/app/pages/settings/payment_gateway"
                            )
                    },

                    // mail_settings
                    {
                        name: "mail_settings",
                        path: "mail_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "mail_settings" */ "./views/app/pages/settings/mail_settings"
                            )
                    },

                    // module_settings
                    {
                        name: "module_settings",
                        path: "module_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "module_settings" */ "./views/app/pages/settings/module_settings"
                            )
                    },

                    // update_settings
                    {
                        name: "update_settings",
                        path: "update_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "update_settings" */ "./views/app/pages/settings/update_settings"
                            )
                    },

                    // currencies
                    {
                        name: "currencies",
                        path: "Currencies",
                        component: () =>
                            import(
                                /* webpackChunkName: "Currencies" */ "./views/app/pages/settings/currencies"
                            )
                    },

                    // Backup
                    {
                        name: "Backup",
                        path: "Backup",
                        component: () =>
                            import(
                                /* webpackChunkName: "Backup" */ "./views/app/pages/settings/backup"
                            )
                    },

                    // Warehouses
                    {
                        name: "Warehouses",
                        path: "Warehouses",
                        component: () =>
                            import(
                                /* webpackChunkName: "Warehouses" */ "./views/app/pages/settings/warehouses"
                            )
                    },

                    // System Settings
                    {
                        name: "system_settings",
                        path: "System_settings",
                        component: () =>
                            import(
                                /* webpackChunkName: "System_settings" */ "./views/app/pages/settings/system_settings"
                            )
                    }

                ]
            },

            // Reports
            {
                path: "/app/reports",
                component: () => import("./views/app/pages/reports"),
                redirect: "/app/reports/profit_and_loss",
                children: [
                    {
                        name: "payments_purchases",
                        path: "payments_purchase",
                        component: () =>
                            import(
                                /* webpackChunkName: "payments_purchases" */
                                "./views/app/pages/reports/payments/payments_purchases"
                            )
                    },
                    {
                        name: "payments_sales",
                        path: "payments_sale",
                        component: () =>
                            import(
                                /* webpackChunkName: "payments_sales" */
                                "./views/app/pages/reports/payments/payments_sales"
                            )
                    },
                    {
                        name: "payments_purchases_returns",
                        path: "payments_purchases_returns",
                        component: () =>
                            import(
                                /* webpackChunkName: "payments_purchases_returns" */
                                "./views/app/pages/reports/payments/payments_purchases_returns"
                            )
                    },
                    {
                        name: "payments_sales_returns",
                        path: "payments_sales_returns",
                        component: () =>
                            import(
                                /* webpackChunkName: "payments_sales_returns" */
                                "./views/app/pages/reports/payments/payments_sales_returns"
                            )
                    },

                    {
                        name: "report_transactions",
                        path: "report_transactions",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_transactions" */
                                "./views/app/pages/reports/report_transactions"
                            )
                    },

                    {
                        name: "report_sales_by_category",
                        path: "report_sales_by_category",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_sales_by_category" */
                                "./views/app/pages/reports/report_sales_by_category"
                            )
                    },

                    {
                        name: "report_sales_by_brand",
                        path: "report_sales_by_brand",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_sales_by_brand" */
                                "./views/app/pages/reports/report_sales_by_brand"
                            )
                    },



                    {
                        name: "profit_and_loss",
                        path: "profit_and_loss",
                        component: () =>
                            import(
                                /* webpackChunkName: "profit_and_loss" */
                                "./views/app/pages/reports/profit_and_loss"
                            )
                    },

                    {
                        name: "inventory_valuation_summary",
                        path: "inventory_valuation_summary",
                        component: () =>
                            import(
                                /* webpackChunkName: "inventory_valuation_summary" */
                                "./views/app/pages/reports/inventory_valuation_summary"
                            )
                    },

                    {
                        name: "expenses_report",
                        path: "expenses_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "expenses_report" */
                                "./views/app/pages/reports/expenses_report"
                            )
                    },

                    {
                        name: "deposits_report",
                        path: "deposits_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "deposits_report" */
                                "./views/app/pages/reports/deposits_report"
                            )
                    },


                    {
                        name: "quantity_alerts",
                        path: "quantity_alerts",
                        component: () =>
                            import(
                                /* webpackChunkName: "quantity_alerts" */
                                "./views/app/pages/reports/quantity_alerts"
                            )
                    },
                    {
                        name: "warehouse_report",
                        path: "warehouse_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "warehouse_report" */
                                "./views/app/pages/reports/warehouse_report"
                            )
                    },

                    {
                        name: "sales_report",
                        path: "sales_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "sales_report" */
                                "./views/app/pages/reports/sales_report"
                            )
                    },

                    {
                        name: "product_sales_report",
                        path: "product_sales_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "product_sales_report" */
                                "./views/app/pages/reports/product_sales_report"
                            )
                    },
                    {
                        name: "purchase_report",
                        path: "purchase_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "purchase_report" */
                                "./views/app/pages/reports/purchase_report"
                            )
                    },

                    {
                        name: "product_purchases_report",
                        path: "product_purchases_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "product_purchases_report" */
                                "./views/app/pages/reports/product_purchases_report"
                            )
                    },

                    {
                        name: "customers_report",
                        path: "customers_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "customers_report" */
                                "./views/app/pages/reports/customers_report"
                            )
                    },
                    {
                        name: "detail_customer_report",
                        path: "detail_customer/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_customer_report" */
                                "./views/app/pages/reports/detail_Customer_Report"
                            )
                    },

                    {
                        name: "providers_report",
                        path: "providers_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "providers_report" */
                                "./views/app/pages/reports/providers_report"
                            )
                    },
                    {
                        name: "detail_supplier_report",
                        path: "detail_supplier/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_supplier_report" */
                                "./views/app/pages/reports/detail_Supplier_Report"
                            )
                    },

                    {
                        name: "top_selling_products",
                        path: "top_selling_products",
                        component: () =>
                            import(
                                /* webpackChunkName: "top_selling_products" */
                                "./views/app/pages/reports/top_selling_products"
                            )
                    },

                    {
                        name: "product_report",
                        path: "product_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "product_report" */
                                "./views/app/pages/reports/product_report"
                            )
                    },
                    {
                        name: "detail_product_report",
                        path: "detail_product/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_product_report" */
                                "./views/app/pages/reports/detail_product_report"
                            )
                    },

                    {
                        name: "top_customers",
                        path: "top_customers",
                        component: () =>
                            import(
                                /* webpackChunkName: "top_customers" */
                                "./views/app/pages/reports/top_customers"
                            )
                    },

                    {
                        name: "stock_report",
                        path: "stock_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "stock_report" */
                                "./views/app/pages/reports/stock_report"
                            )
                    },
                    {
                        name: "detail_stock_report",
                        path: "detail_stock/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_stock_report" */
                                "./views/app/pages/reports/detail_stock_report"
                            )
                    },

                    {
                        name: "users_report",
                        path: "users_report",
                        component: () =>
                            import(
                                /* webpackChunkName: "users_report" */
                                "./views/app/pages/reports/users_report"
                            )
                    },
                    {
                        name: "detail_user_report",
                        path: "detail_user/:id",
                        component: () =>
                            import(
                                /* webpackChunkName: "detail_user_report" */
                                "./views/app/pages/reports/detail_user_report"
                            )
                    },

                    {
                        name: "report_error_logs",
                        path: "report_error_logs",
                        component: () =>
                            import(
                                /* webpackChunkName: "report_error_logs" */
                                "./views/app/pages/reports/report_error_logs"
                            )
                    },
                ]
            },

            {
                name: "profile",
                path: "/app/profile",
                component: () =>
                    import(
                        /* webpackChunkName: "profile" */ "./views/app/pages/profile"
                    )
            }
        ]
    },

    // {
    //     name: 'register',
    //     path: '/register',
    //     component: () => import('@/views/Register.vue'),
    // },

    {
        name: "pos",
        path: "/app/pos",
        // beforeEnter: authenticate,
        component: () =>
            import(/* webpackChunkName: "pos" */ "./views/app/pages/pos")
    },

    {
        name: "pos_draft",
        path: "/app/pos/create/:id",
        component: () =>
            import(
                /* webpackChunkName: "pos_draft" */ "./views/app/pages/pos_draft"
            )
    },

    {
        path: "*",
        name: "NotFound",
        component: () =>
            import(
                /* webpackChunkName: "NotFound" */ "./views/app/pages/notFound"
            )
    },

    {
        path: "not_authorize",
        name: "not_authorize",
        component: () =>
            import(
                /* webpackChunkName: "not_authorize" */ "./views/app/pages/NotAuthorize"
            )
    }
];

const router = new Router({
    mode: "history",
    linkActiveClass: "open",
    routes: baseRoutes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 };
    }
});

// Fix redundant navigation error
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location, onResolve, onReject) {
    if (onResolve || onReject)
        return originalPush.call(this, location, onResolve, onReject);
    return originalPush.call(this, location).catch(err => err);
};

// ✅ Export function to set up navigation guards
export function setupRouterGuards(i18n) {
    router.beforeEach(async (to, from, next) => {
        if (to.path) {
            NProgress.start();
            NProgress.set(0.1);
        }

        const savedLang = store.state.language.language;

        if (savedLang && savedLang !== i18n.locale) {
            i18n.locale = savedLang;
        } else if (!savedLang) {
            await store.dispatch("language/setLanguage", navigator.languages);
            i18n.locale = store.state.language.language;
        }

        next();
    });

    router.afterEach(() => {
        const gullPreLoading = document.getElementById("loading_wrap");
        if (gullPreLoading) {
            gullPreLoading.style.display = "none";
        }

        setTimeout(() => NProgress.done(), 500);

        if (window.innerWidth <= 1200) {
            store.dispatch("changeSidebarProperties");

            if (store.getters.getSideBarToggleProperties.isSecondarySideNavOpen) {
                store.dispatch("changeSecondarySidebarProperties");
            }

            if (store.getters.getCompactSideBarToggleProperties.isSideNavOpen) {
                store.dispatch("changeCompactSidebarProperties");
            }
        } else {
            if (store.getters.getSideBarToggleProperties.isSecondarySideNavOpen) {
                store.dispatch("changeSecondarySidebarProperties");
            }
        }
    });
}


async function Check_Token(to, from, next) {
    let token = to.params.token;
    const res = await axios
        .get("password/find/" + token)
        .then(response => response.data);

    if (!res.success) {
        next("/app/sessions/signIn");
    } else {
        return next();
    }
}

export default router;
