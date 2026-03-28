# Supply Radar API Reference

> [!NOTE]
> All endpoints under `Customer Endpoints` require a valid Sanctum Bearer token passed in the `Authorization` header.

## Public Endpoints

### `POST /api/register`
Creates a brand new Company and User simultaneously.
**Payload:**
```json
{
  "company_name": "CargoCorp",
  "user_name": "Jane Doe",
  "email": "jane@cargocorp.com",
  "password": "securepassword123"
}
```
**Response:** Returns User, Company, and your `token`.

---

### `POST /api/login`
**Payload:** `email`, `password`.
**Response:** Returns `token`.

---

### `GET /api/disruptions`
Lists all global disruptions (unprotected).

---

## Customer Endpoints
*(Requires `Authorization: Bearer {token}`)*

### `POST /api/logout`
Destroys the current access token.

---

### `GET /api/supply-locations`
Returns all supply locations belonging to the authenticated User's company.

---

### `POST /api/supply-locations`
Creates a new supply location and automatically assigns it to your company.
**Payload:** 
```json
{
  "name": "Tokyo Port Warehouse",
  "country": "JP",
  "latitude": 35.6762,
  "longitude": 139.6503,
  "industry": "Shipping"
}
```

---

### `DELETE /api/supply-locations/{id}`
Deletes a specific supply location. Will throw a 404 if the location belongs to a *different* company, preventing unauthorized access.

---

### `GET /api/alerts`
Returns paginated Disruption Alerts actively affecting *your* company specifically. Included in the response is the related [disruption](file:///var/www/html/supply-radar-laravel/app/Models/Alert.php#29-33) data so you can display it natively in the dashboard.
