# MyTheresa Product Catalog API

A product catalog API implementing discount rules for the MyTheresa technical challenge.

## Quick Start

### Prerequisites
- Docker & Docker Compose

### Run the Application
```bash
make start
```

This single command will:
- Build and start containers
- Create database and run migrations
- Seed sample products
- Make the API available at http://localhost

### Run Tests
```bash
make test
```

## API Usage

### Endpoints
- **GET /products** - Get product catalog (max 5 products)
- **GET /api/doc** - API documentation

### Sample Response
```json
{
  "products": [
    {
      "sku": "000001",
      "name": "BV Lean leather ankle boots",
      "category": "boots",
      "price": {
        "original": 89000,
        "final": 62300,
        "discount_percentage": "30%",
        "currency": "EUR"
      }
    }
  ]
}
```

## Business Rules

The API applies the following discount rules:

1. **Boots category discount**: 30% off all products in "boots" category
2. **Special SKU discount**: 15% off product with SKU "000003"
3. **Multiple discounts**: When both apply, the higher discount is used

**Important**: Price filtering is applied BEFORE discounts are calculated.

## Architecture

Built with:
- **Symfony 7.3** with **FrankenPHP**
- **Domain-Driven Design** patterns
- **Hexagonal Architecture**
- **CQS** with separate query handlers
- **PostgreSQL** database
- **Docker** containerization
- **PHPUnit** for unit testing

## Architecture Decisions & Future Improvements

### Current Implementation Decisions

**Domain-Driven Design Approach**
- **Category as Value Object** - Chosen for simplicity, though in real business scenarios would likely be an Entity with additional properties (name, description, slug) and unique identifier.
- **SKU as Business Identifier** - Used for API compatibility, though **UUID would be more universal** as SKUs can change over time

**Testing Strategy**
- **Unit tests prioritized** with test doubles (ProductRepositoryFake) for fast, isolated testing
- **Domain and use case focus** - Tested core business logic thoroughly without external dependencies
- Integration and E2E tests omitted for challenge scope, but would be ideal for production

### Performance & Scalability Improvements

**Database Optimization (for 20k+ products)**
```sql
-- Option 1: Pre-calculated catalog projection (single SELECT)
CREATE TABLE catalog_projection AS 
SELECT id, sku, name, price, discount_percentage, final_price 
FROM products_with_discounts;

-- Option 2: Separate discount projection (LEFT JOIN)
CREATE TABLE discount_projection AS
SELECT product_id, discount_percentage, final_price
FROM calculated_discounts;
```

**Event-Driven Projections**: When a product is created or updated, the projection tables are automatically updated via domain events. For simpler implementation, this can initially be done with **cronjobs** that rebuild projections periodically.

**Benefits**: Simplified query handler, faster response times, easier testing the query handler, eventual consistency

**OpenSearch/Elasticsearch** for complex filtering at scale (millions of products)

**Repository Pattern Enhancement**
```php
// Current: Parameter-based filtering
$repository->find($category, $maxPrice, $limit);

// Future: Criteria-based approach
$repository->findByCriteria(
    ProductSearchCriteria::create()
        ->withCategory($category)
        ->withMaxPrice($maxPrice)
        ->withLimit($limit)
);
```

**Trade-offs**: 
- ✅ More flexible and composable queries
- ❌ Harder to understand for simple use cases

## Development

```bash
# View all available commands
make help

# Stop containers
make down

# View logs
make logs

# Access container shell
make bash
```
