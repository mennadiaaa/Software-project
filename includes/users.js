const users = {
  staff: [
    {
      id: "staff_nour",
      firstName: "Nour",
      lastName: "Khalil",
      email: "nour.khalil@revolvingrestaurant.com",
      password: "Password123!",
      role: "staff",
      assignedRestaurant: "The Revolving Restaurant"
    },
    {
      id: "staff_tarek",
      firstName: "Tarek",
      lastName: "Mansour",
      email: "tarek.mansour@lepetitechef.com",
      password: "Password123!",
      role: "staff",
      assignedRestaurant: "Le Petite Chef"
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
      id: "cust_sarah",
      firstName: "Sarah",
      lastName: "Hassan",
      email: "sarah.hassan@example.com",
      password: "CustomerPass123!",
      role: "customer"
    },
    {
      id: "cust_youssef",
      firstName: "Youssef",
      lastName: "Ali",
      email: "youssef.ali@example.com",
      password: "CustomerPass123!",
      role: "customer"
    },
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
      id: "admin_system",
      firstName: "System",
      lastName: "Admin",
      email: "admin@savoure.com",
      password: "AdminSecurePass123!",
      role: "admin"
    },
    {
      id: "admin_01",
      firstName: "Rana",
      lastName: "Moahmed",
      email: "Rana_Mohamed@admin.com",
      password: "AdminSecurePass123!",
      role: "admin"
    }
  ]
};

if (typeof module !== 'undefined' && module.exports) {
  module.exports = users;
}
