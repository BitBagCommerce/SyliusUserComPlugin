# Functionalities 

## Overview
The **BitBagSyliusUserComPlugin** integrates **User.com** with Sylius-based stores, enabling customer data synchronization, automated event tracking, and marketing automation capabilities.

## Features

### 1. User.com API Integration
- Facilitates communication with the **User.com API** for seamless data synchronization.

### 2. Customer Data Synchronization
- **Triggers requests to User.com** upon critical customer actions:
    - Providing **billing information** during checkout.
    - **Editing profile** from the customer account.
    - **Editing profile** from the admin panel.
    - **Customer registration**.

### 3. Order Tracking & Synchronization
- Sends **order details** to User.com upon every state change of the **main order status**.
- Ensures **real-time order tracking** and **customer behavior insights**.

### 4. Event-Driven System
- Each customer interaction generates an **event**, which is stored and sent to **User.com** for automation and reporting.

### 5. Product Persistence & Feed Generation
- **Persists products** within the system for accurate data reporting.
- Generates a **product feed** that can be used for marketing and analytics purposes.

### 6. Tag Manager Script Injection
- Allows users to **inject custom scripts** via **Tag Manager**.
- Enables integration with **third-party tracking tools**.

### 7. User information object 
- you can use `user_com_customer_info` in browser console to check currently logged in customer data
