# Modèle conceptuel de données (MCD) — SI Boutique Gaming

## 1. Diagramme entité-association (MCD)

```mermaid
erDiagram
    ROLE ||--o{ USER : "est attribué à"
    USER ||--o{ SALE : "enregistre"
    USER ||--o{ STOCK_MOVEMENT : "effectue"
    USER ||--o{ ACTIVITY_LOG : "génère"
    USER ||--o| DRIVER : "peut être"

    CATEGORY ||--o{ PRODUCT : "classifie"

    SUPPLIER ||--o{ STOCK_MOVEMENT : "approvisionne"

    PRODUCT ||--o{ SALE_ITEM : "compose"
    PRODUCT ||--o{ STOCK_MOVEMENT : "concerne"

    CUSTOMER ||--o{ SALE : "passe"
    CUSTOMER ||--o{ DELIVERY : "reçoit"
    CUSTOMER ||--o{ INVOICE : "destinataire"

    SALE ||--|{ SALE_ITEM : "contient"
    SALE ||--o| INVOICE : "produit"
    SALE ||--o| DELIVERY : "entraîne"

    DRIVER ||--o{ DELIVERY : "assure"
    DRIVER ||--o{ DELIVERY_STATUS_HISTORY : "responsable"

    DELIVERY ||--o{ DELIVERY_STATUS_HISTORY : "historise"

    USER ||--o{ NOTIFICATION : "reçoit"
    USER ||--o{ DELIVERY_STATUS_HISTORY : "modifie"

    ROLE {
        bigint id PK
        string name UK
        string slug UK
        text description
        json permissions
        timestamps created_at updated_at
    }

    USER {
        bigint id PK
        bigint role_id FK
        string name
        string email UK
        string password
        string phone
        boolean is_active
        timestamps created_at updated_at
    }

    CATEGORY {
        bigint id PK
        string name UK
        string slug UK
        text description
        boolean is_active
        timestamps created_at updated_at
    }

    PRODUCT {
        bigint id PK
        bigint category_id FK
        string reference UK
        string barcode UK
        string name
        text description
        string brand
        decimal purchase_price
        decimal sale_price
        int stock_quantity
        int minimum_stock
        string image
        boolean is_active
        timestamps created_at updated_at
    }

    SUPPLIER {
        bigint id PK
        string name
        string phone
        string email
        text address
        string country
        boolean is_active
        timestamps created_at updated_at
    }

    CUSTOMER {
        bigint id PK
        string full_name
        string phone
        string email
        text address
        string city
        date registered_at
        timestamps created_at updated_at
    }

    SALE {
        bigint id PK
        string sale_number UK
        bigint customer_id FK
        bigint user_id FK
        date sale_date
        decimal discount_amount
        decimal tax_rate
        decimal subtotal_ht
        decimal tax_amount
        decimal total_ttc
        enum status
        text notes
        timestamps created_at updated_at
    }

    SALE_ITEM {
        bigint id PK
        bigint sale_id FK
        bigint product_id FK
        int quantity
        decimal unit_price
        decimal discount
        decimal line_total
        timestamps created_at updated_at
    }

    INVOICE {
        bigint id PK
        string invoice_number UK
        bigint sale_id FK UK
        bigint customer_id FK
        date issued_at
        decimal subtotal_ht
        decimal tax_amount
        decimal total_ttc
        enum status
        string pdf_path
        timestamps created_at updated_at
    }

    DRIVER {
        bigint id PK
        bigint user_id FK
        string name
        string phone
        string email
        string delivery_zone
        boolean is_active
        timestamps created_at updated_at
    }

    DELIVERY {
        bigint id PK
        string delivery_number UK
        bigint sale_id FK
        bigint customer_id FK
        bigint driver_id FK
        text address
        string phone
        date scheduled_date
        datetime delivered_at
        enum status
        text notes
        decimal latitude
        decimal longitude
        timestamps created_at updated_at
    }

    DELIVERY_STATUS_HISTORY {
        bigint id PK
        bigint delivery_id FK
        bigint driver_id FK
        bigint changed_by FK
        enum status
        text notes
        timestamp created_at
    }

    STOCK_MOVEMENT {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        bigint supplier_id FK
        enum type
        int quantity
        int quantity_before
        int quantity_after
        string reason
        string reference
        timestamps created_at updated_at
    }

    NOTIFICATION {
        uuid id PK
        bigint user_id FK
        string type
        string title
        text message
        json data
        timestamp read_at
        timestamps created_at updated_at
    }

    ACTIVITY_LOG {
        bigint id PK
        bigint user_id FK
        string action
        string model_type
        bigint model_id
        text description
        string ip_address
        string user_agent
        timestamps created_at updated_at
    }
```

---

## 2. Dictionnaire des données

### ROLE
| Attribut | Type | Contrainte | Description |
|----------|------|------------|-------------|
| id | BIGINT | PK, AI | Identifiant unique |
| name | VARCHAR(50) | NOT NULL, UNIQUE | Nom du rôle |
| slug | VARCHAR(50) | NOT NULL, UNIQUE | Identifiant technique |
| description | TEXT | NULL | Description du rôle |
| permissions | JSON | NULL | Permissions sérialisées |

### USER
| Attribut | Type | Contrainte | Description |
|----------|------|------------|-------------|
| id | BIGINT | PK, AI | Identifiant unique |
| role_id | BIGINT | FK → roles.id | Rôle attribué |
| name | VARCHAR(100) | NOT NULL | Nom complet |
| email | VARCHAR(150) | NOT NULL, UNIQUE | Email de connexion |
| password | VARCHAR(255) | NOT NULL | Mot de passe hashé |
| phone | VARCHAR(20) | NULL | Téléphone |
| is_active | BOOLEAN | DEFAULT true | Compte actif |

### PRODUCT
| Attribut | Type | Contrainte | Description |
|----------|------|------------|-------------|
| reference | VARCHAR(50) | NOT NULL, UNIQUE | Référence interne |
| barcode | VARCHAR(50) | NULL, UNIQUE | Code-barres EAN |
| purchase_price | DECIMAL(12,2) | NOT NULL | Prix d'achat HT |
| sale_price | DECIMAL(12,2) | NOT NULL | Prix de vente HT |
| stock_quantity | INT | DEFAULT 0 | Quantité en stock |
| minimum_stock | INT | DEFAULT 5 | Seuil d'alerte |
| brand | VARCHAR(100) | NULL | Marque (Sony, Nintendo…) |

### SALE — Statuts
| Valeur | Description |
|--------|-------------|
| draft | Brouillon, modifiable |
| validated | Validée, stock déduit, facture générée |
| cancelled | Annulée |

### DELIVERY — Statuts
| Valeur | Description |
|--------|-------------|
| pending | En attente |
| assigned | Assignée à un livreur |
| in_progress | En cours de livraison |
| delivered | Livrée |
| failed | Échouée |
| cancelled | Annulée |

### STOCK_MOVEMENT — Types
| Valeur | Description |
|--------|-------------|
| entry | Entrée stock (approvisionnement) |
| exit | Sortie stock (hors vente) |
| adjustment | Ajustement / inventaire |
| sale | Sortie liée à une vente |
| return | Retour stock (annulation vente) |

---

## 3. Schéma relationnel (MySQL)

### 3.1 Tables et clés

```
roles (id PK)
    ↑
users (id PK, role_id FK → roles.id)

categories (id PK)
    ↑
products (id PK, category_id FK → categories.id)

suppliers (id PK)

customers (id PK)

sales (id PK, customer_id FK → customers.id, user_id FK → users.id)

sale_items (id PK, sale_id FK → sales.id, product_id FK → products.id)

invoices (id PK, sale_id FK UNIQUE → sales.id, customer_id FK → customers.id)

drivers (id PK, user_id FK NULL → users.id)

deliveries (id PK, sale_id FK → sales.id, customer_id FK → customers.id, driver_id FK → drivers.id)

delivery_status_histories (id PK, delivery_id FK → deliveries.id, driver_id FK → drivers.id, changed_by FK → users.id)

stock_movements (id PK, product_id FK → products.id, user_id FK → users.id, supplier_id FK NULL → suppliers.id)

notifications (id PK UUID, user_id FK → users.id)

activity_logs (id PK, user_id FK → users.id)
```

### 3.2 Index recommandés

| Table | Index | Type |
|-------|-------|------|
| products | reference, barcode, category_id, name | INDEX |
| sales | sale_number, sale_date, status, customer_id | INDEX |
| sale_items | sale_id, product_id | INDEX |
| invoices | invoice_number, issued_at | INDEX |
| deliveries | delivery_number, status, driver_id | INDEX |
| stock_movements | product_id, type, created_at | INDEX |
| customers | full_name, phone, email | INDEX |
| activity_logs | user_id, model_type+model_id, created_at | INDEX |

### 3.3 Contraintes d'intégrité

- `ON DELETE RESTRICT` sur les FK métier (ventes, produits, clients)
- `ON DELETE SET NULL` sur `deliveries.driver_id`, `drivers.user_id`
- `ON DELETE CASCADE` sur `sale_items` (si suppression vente brouillon)
- `CHECK stock_quantity >= 0` (niveau application + trigger optionnel)
- Unicité : `sale_number`, `invoice_number`, `delivery_number`, `product.reference`

### 3.4 Cardinalités résumées

| Relation | Cardinalité |
|----------|-------------|
| Role → User | 1,N |
| Category → Product | 1,N |
| Customer → Sale | 1,N |
| Sale → SaleItem | 1,N |
| Sale → Invoice | 1,0..1 |
| Sale → Delivery | 1,0..1 |
| Driver → Delivery | 1,N |
| Delivery → DeliveryStatusHistory | 1,N |
| Product → StockMovement | 1,N |
| User → Driver | 1,0..1 |
