# Technical Database Schema (ERD)

This document provides a technical map of the CLEANSETZ SHOE CARE database, including entity relationships and field descriptions.

## Mermaid ERD (Crow's Foot Notation)

```mermaid
erDiagram
    USERS ||--o{ ORDERS : "makes"
    USERS ||--o{ EXPENSES : "records"
    ORDERS ||--|{ ORDER_ITEMS : "contains"
    ORDER_ITEMS }|--|| TREATMENTS : "has"
    EXPENSES }|--|| EXPENSE_CATEGORIES : "belongs to"

    USERS {
        bigint id PK
        string name
        string email
        string password
        enum role "owner, admin, customer"
        string phone
        text address
        timestamp created_at
    }

    TREATMENTS {
        bigint id PK
        string name
        text description
        decimal price
        string estimated_time
        string image
        boolean is_active
    }

    ORDERS {
        bigint id PK
        string order_code
        string qr_code
        bigint customer_id FK
        string customer_name
        string customer_phone
        enum service_method "datang_langsung, pickup_delivery"
        text pickup_address
        datetime pickup_date
        decimal pickup_fee
        decimal total_price
        string status
        enum payment_status "lunas, belum_lunas"
        string payment_proof
        string payment_method
        datetime payment_date
        datetime estimated_completion
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        string shoe_brand
        string shoe_material
        string shoe_color
        bigint treatment_id FK
        decimal price
        string photo_before
        string photo_after
    }

    EXPENSES {
        bigint id PK
        date date
        bigint expense_category_id FK
        text description
        decimal amount
        string payment_method
        string proof_of_payment
        bigint user_id FK
    }

    EXPENSE_CATEGORIES {
        bigint id PK
        string name
    }
```

---

## Entity Descriptions

### 1. Users
Stores information for all actors (Owners, Admins, and Customers). The `role` column determines dashboard access and permissions.

### 2. Treatments (Services)
The catalog of shoe care services. Each treatment has a price and estimated processing time.

### 3. Orders
The main transaction record. It tracks the customer, service method, logistics details (address/fee), and payment confirmation.

### 4. Order Items (Shoes)
Represents the individual pairs of shoes within an order. Each item is linked to a specific treatment.

### 5. Expenses
Tracks operational spending. Each record is categorized and linked to the Admin who entered the data.

### 6. Expense Categories
A lookup table for organizing expenses (e.g., Supplies, Utilities, Rent).
