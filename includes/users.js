const users = {
staff: [
{
id: "staff_01",
firstName: "Sarah",
lastName: "Jenkins",
email: "sarah.jenkins@revolvingrestaurant.com",
password: "Password123!",
role: "staff",
assignedRestaurant: "Revolving Restaurant"
},
{
id: "staff_02",
firstName: "Pierre",
lastName: "Dubois",
email: "pierre.dubois@lepetitechef.com",
password: "Password123!",
role: "staff",
assignedRestaurant: "Le Petite Chef"
},
{
id: "staff_03",
firstName: "Michael",
lastName: "Ross",
email: "michael.ross@mobistro.com",
password: "Password123!",
role: "staff",
assignedRestaurant: "Mo Bistro"
}
],
customers: [
{
id: "cust_01",
firstName: "Alice",
lastName: "Johnson",
email: "alice.johnson@example.com",
password: "CustomerPass123!",
role: "customer"
},
{
id: "cust_02",
firstName: "David",
lastName: "Smith",
email: "david.smith@example.com",
password: "CustomerPass123!",
role: "customer"
}
],
admins: [
{
id: "admin_01",
firstName: "Alexander",
lastName: "Vance",
email: "alex.vance@admin.com",
password: "AdminSecurePass123!",
role: "admin"
}
]
};

module.exports = users;