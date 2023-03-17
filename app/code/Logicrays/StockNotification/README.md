# About  Extension
This extension allows customers to subscribe to out-of-stock products, and when they are back in stock, they are notified via email and text message.

# Related GraphQL
* type StoreConfig

    type StoreConfig {
        stocknotification_enable : String @doc(description: "To fetch value of module enable or disable"),
        stocknotification_customerlist : String @doc(description: "fetch value of customer list"),
        stocknotification_notificationtype : String @doc(description: "fetch value of notification type"),
        stocknotification_sender_email_identity : String @doc(description: "fetch value of sender email"),
        stocknotification_admin_email_notification : String @doc(description: "fetch value of email notification"),
        stocknotification_email_template : String @doc(description: "fetch value of template"),
        stocknotification_email_template_admin : String @doc(description: "fetch value of template id for admin messages"),
        stocknotification_email_template_instock : String @doc(description: "fetch value of template id for in stock messages"),
        stocknotification_smsmsg91senderid : String @doc(description: "fetch value of id"),
        stocknotification_smsmsg91authkey : String @doc(description: "fetch value of key"),
        stocknotification_smsmsg91apiurl : String @doc(description: "fetch value of url"),
        stocknotification_template : String @doc(description: "fetch value of template"),
        stocknotification_enableadmin : String @doc(description: "fetch value of admin enable"),
        stocknotification_mobilenumber : String @doc(description: "fetch value of mobile number"),
        stocknotification_admintemplate : String @doc(description: "fetch value of admin template"),
        stocknotification_notification_title : String @doc(description: "fetch value of notification title"),
        stocknotification_button_title : String @doc(description: "fetch value of button title"),
        stocknotification_sucsess_message : String @doc(description: "fetch value of sucess message")
    }

-> This graphql returns all of the admin-side configuration values.

**Please Note.**

1. **stocknotification_enable**: In this field, you get an output of 1, and only then does this extension function in your store.

2. **stocknotification_customerlist**: In this, you get whose customer is applicable for this functionality, so according to this,
    you have to check from your side. Like "Not Logged In," "General," "Wholesaler," and "Retailer," you will only get the customer's group ID. So match according to this.
    For Example: Suppose at your frontend side one customer is logged in then you have get group id of that customer for fronted side, customer group id and backend side customer group id then you have to check condition if both are equal customer group id then and then it will work. If it does not satisfy your condition, then this functionality will not work anymore!

3. **stocknotification_notification_title**: In this, you will get some text as a form title when some products are out of
    stock. This title will then be shown in the form available on the product detail page.
    For Example: Notify is title of the Form.

4. **stocknotification_button_title**: In this, you will get some text as a form button title when some products are out of stock.
    This button title will then be shown in the form available on the product detail page.
    For Example: Notify Me is button title of the Form.

5. **stocknotification_sucsess_message**: You will get some string that will appear when the customer clicks on the button and
    successfully submits the form.  

* stockNotificationFormSubmit

    input StockNotificationFormInput {
        websitename: String @doc(description: "Website Name")
        product_id: Int @doc(description: "The Product Id")
        product_name: String @doc(description: "The Product Name")
        product_sku: String @doc(description: "The Product Sku")
        subscriber_name: String @doc(description: "Customer Name if not logged in customer then Guest")
        subscriber_email: String @doc(description: "Customer Email")
        subscriber_mobile: Int @doc(description: "Customer Mobile")
    }
    type StockNotificationFormOutput {
        success: String
        message: String
    }

-> When you submit the form from the front end, then you have to call the above graphql, and in this graphql, you have to pass all
    the data mentioned above.
-> In this one field, subscriber_name, you have to pass the current customer's name if the customer is not logged in. In that case,
    you have to pass guest as a string. 
-> If the data is successfully stored in the database from the front end, this graphql will return a success value and a message.
-> or if you try to store those data that are already in the database, then you will receive a message related to that.