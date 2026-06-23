# Diagrammes UML — SI Boutique Gaming

## 1. Diagramme de cas d'utilisation

```mermaid
flowchart TB
    subgraph Acteurs
        Admin((Administrateur))
        Manager((Gestionnaire))
        Cashier((Caissier))
        Driver((Livreur))
    end

    subgraph Authentification
        UC01[Se connecter]
        UC02[Se déconnecter]
        UC03[Réinitialiser mot de passe]
    end

    subgraph Gestion_Utilisateurs
        UC04[Gérer utilisateurs]
        UC05[Gérer rôles]
    end

    subgraph Catalogue
        UC06[Gérer produits]
        UC07[Gérer catégories]
        UC08[Importer/Exporter Excel]
    end

    subgraph Stock
        UC09[Gérer entrées stock]
        UC10[Gérer sorties stock]
        UC11[Effectuer inventaire]
        UC12[Consulter historique stock]
        UC13[Recevoir alertes stock]
    end

    subgraph Fournisseurs
        UC14[Gérer fournisseurs]
        UC15[Consulter historique appro]
    end

    subgraph Clients
        UC16[Gérer clients]
        UC17[Consulter historique achats]
    end

    subgraph Ventes_Factures
        UC18[Créer vente]
        UC19[Valider vente]
        UC20[Annuler vente]
        UC21[Générer facture PDF]
        UC22[Consulter factures]
    end

    subgraph Livraisons
        UC23[Gérer livraisons]
        UC24[Affecter livreur]
        UC25[Mettre à jour statut livraison]
        UC26[Suivre livraison]
    end

    subgraph Livreurs
        UC27[Gérer livreurs]
        UC28[Consulter missions]
    end

    subgraph Reporting
        UC29[Consulter dashboard]
        UC30[Générer rapports]
        UC31[Exporter PDF/Excel]
    end

    subgraph Notifications
        UC32[Recevoir notifications]
    end

    Admin --> UC01 & UC02 & UC03 & UC04 & UC05
    Admin --> UC06 & UC07 & UC08 & UC09 & UC10 & UC11 & UC12
    Admin --> UC14 & UC15 & UC16 & UC17 & UC18 & UC19 & UC20
    Admin --> UC21 & UC22 & UC23 & UC24 & UC27 & UC29 & UC30 & UC31

    Manager --> UC01 & UC02 & UC03
    Manager --> UC06 & UC07 & UC08 & UC09 & UC10 & UC11 & UC12 & UC13
    Manager --> UC14 & UC15 & UC18 & UC19 & UC20 & UC23 & UC24 & UC27
    Manager --> UC29 & UC30 & UC31 & UC32

    Cashier --> UC01 & UC02 & UC03
    Cashier --> UC16 & UC17 & UC18 & UC19 & UC20 & UC21 & UC22 & UC32

    Driver --> UC01 & UC02 & UC03
    Driver --> UC25 & UC26 & UC28 & UC32
```

---

## 2. Diagramme de classes (domaine métier)

```mermaid
classDiagram
    class User {
        +bigint id
        +bigint role_id
        +string name
        +string email
        +string password
        +string phone
        +boolean is_active
        +hasRole()
        +isAdmin()
    }

    class Role {
        +bigint id
        +string name
        +string slug
        +json permissions
    }

    class Category {
        +bigint id
        +string name
        +string slug
        +boolean is_active
    }

    class Product {
        +bigint id
        +string reference
        +string barcode
        +string name
        +decimal purchase_price
        +decimal sale_price
        +int stock_quantity
        +int minimum_stock
        +boolean is_active
        +isLowStock()
        +isOutOfStock()
    }

    class Supplier {
        +bigint id
        +string name
        +string phone
        +string email
        +string address
        +string country
    }

    class Customer {
        +bigint id
        +string full_name
        +string phone
        +string email
        +string address
        +string city
    }

    class Sale {
        +bigint id
        +string sale_number
        +date sale_date
        +decimal subtotal_ht
        +decimal tax_amount
        +decimal total_ttc
        +enum status
        +validate()
        +cancel()
    }

    class SaleItem {
        +bigint id
        +int quantity
        +decimal unit_price
        +decimal discount
        +decimal line_total
    }

    class Invoice {
        +bigint id
        +string invoice_number
        +date issued_at
        +decimal total_ttc
        +generatePdf()
    }

    class Driver {
        +bigint id
        +string name
        +string phone
        +string delivery_zone
        +boolean is_active
    }

    class Delivery {
        +bigint id
        +string delivery_number
        +string address
        +enum status
        +assignDriver()
        +updateStatus()
    }

    class DeliveryStatusHistory {
        +bigint id
        +string status
        +datetime created_at
    }

    class StockMovement {
        +bigint id
        +enum type
        +int quantity
        +int quantity_before
        +int quantity_after
        +string reason
    }

    class ActivityLog {
        +bigint id
        +string action
        +string description
    }

    User "1" --> "1" Role : possède
    Category "1" --> "*" Product : contient
    Product "1" --> "*" StockMovement : historise
    Supplier "1" --> "*" StockMovement : approvisionne
    Customer "1" --> "*" Sale : effectue
    User "1" --> "*" Sale : enregistre
    Sale "1" --> "*" SaleItem : contient
    SaleItem "*" --> "1" Product : référence
    Sale "1" --> "0..1" Invoice : génère
    Sale "1" --> "0..1" Delivery : déclenche
    Customer "1" --> "*" Delivery : reçoit
    Driver "1" --> "*" Delivery : assure
    Delivery "1" --> "*" DeliveryStatusHistory : trace
    User "1" --> "*" ActivityLog : journalise
```

---

## 3. Diagramme de séquence — Validation d'une vente

```mermaid
sequenceDiagram
    actor Caissier
    participant UI as Interface Blade
    participant SC as SaleController
    participant SS as SaleService
    participant SM as StockService
    participant IS as InvoiceService
    participant DB as Base de données

    Caissier->>UI: Cliquer "Valider vente"
    UI->>SC: POST /sales/{id}/validate
    SC->>SS: validateSale(sale)
    SS->>DB: Vérifier statut = draft
    SS->>DB: Vérifier stock disponible

    alt Stock insuffisant
        SS-->>SC: Exception StockException
        SC-->>UI: Erreur + message
        UI-->>Caissier: Alerte stock insuffisant
    else Stock OK
        loop Pour chaque ligne
            SS->>SM: deductStock(product, qty, sale)
            SM->>DB: INSERT stock_movements
            SM->>DB: UPDATE products.stock_quantity
        end
        SS->>IS: generateInvoice(sale)
        IS->>DB: INSERT invoices
        SS->>DB: UPDATE sales.status = validated
        SS->>DB: INSERT activity_logs
        SS-->>SC: Sale validée + Invoice
        SC-->>UI: Redirect + succès
        UI-->>Caissier: Facture générée
    end
```

---

## 4. Diagramme de séquence — Affectation livraison

```mermaid
sequenceDiagram
    actor Gestionnaire
    participant DC as DeliveryController
    participant DS as DeliveryService
    participant NS as NotificationService
    participant DB as Base de données
    actor Livreur

    Gestionnaire->>DC: POST /deliveries/{id}/assign
    DC->>DS: assignDriver(delivery, driver)
    DS->>DB: UPDATE deliveries (driver_id, status=assigned)
    DS->>DB: INSERT delivery_status_histories
    DS->>NS: notifyDriver(driver, delivery)
    NS->>DB: INSERT notifications
    DS-->>DC: Succès
    DC-->>Gestionnaire: Confirmation

    Livreur->>DC: GET /deliveries (mes livraisons)
    DC->>DB: SELECT WHERE driver_id = user.driver
    DB-->>DC: Liste livraisons assignées
    DC-->>Livreur: Affichage missions
```

---

## 5. Diagramme d'activité — Cycle de vie d'une livraison

```mermaid
stateDiagram-v2
    [*] --> EnAttente : Création livraison
    EnAttente --> Assignee : Affectation livreur
    EnAttente --> Annulee : Annulation
    Assignee --> EnCours : Prise en charge livreur
    Assignee --> Annulee : Annulation
    EnCours --> Livree : Livraison réussie
    EnCours --> Echouee : Échec livraison
    EnCours --> Annulee : Annulation
    Echouee --> EnAttente : Nouvelle tentative
    Livree --> [*]
    Annulee --> [*]
    Echouee --> [*]
```

---

## 6. Diagramme de composants (architecture Laravel)

```mermaid
flowchart TB
    subgraph Presentation
        Blade[Views Blade]
        Bootstrap[Bootstrap 5]
        Charts[Chart.js]
    end

    subgraph HTTP
        Routes[Routes web.php]
        Controllers[Controllers]
        Middleware[Middleware Rôles]
        FormRequests[Form Requests]
    end

    subgraph Business
        Services[Services métier]
        Policies[Policies]
        Repositories[Repositories]
    end

    subgraph Data
        Models[Eloquent Models]
        Migrations[Migrations MySQL]
    end

    subgraph External
        DomPDF[DomPDF]
        Maatwebsite[Maatwebsite Excel]
        Mail[Laravel Mail]
    end

    Blade --> Controllers
    Routes --> Middleware --> Controllers
    Controllers --> FormRequests
    Controllers --> Services
    Services --> Repositories
    Services --> Policies
    Repositories --> Models
    Models --> Migrations
    Services --> DomPDF
    Services --> Maatwebsite
    Services --> Mail
```
