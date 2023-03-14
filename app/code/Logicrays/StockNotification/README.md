# About  Extension
This extension allows customers to subscribe to out-of-stock products, and when they are back in stock, they are notified via email and text message.

# Related GraphQL
1) type StoreConfig

    type StoreConfig {
        stocknotification_enable : String @doc(description: "To fetch value of module enable or disable"),
        stocknotification_customerlist : String @doc(description: "fetch value of customer list"),
        stocknotification_notificationtype : String @doc(description: "fetch value of notification type"),
    }

-> This graphql returns all of the admin-side configuration values.

2) stockNotificationFormSubmit

    type Mutation {
        stockNotificationFormSubmit(input: StockNotificationFormInput!): StockNotificationFormOutput @resolver(class: "\\Logicrays\\StockNotification\\Model\\Resolver\\StockNotificationForm") @doc(description:"save into logicrays_stocknotification_records")
}
-> If the data is successfully stored in the database from the front end, this graphql will return a success value and a message.
-> or if you try to store those data that are already in the database, then you will receive a message related to that.