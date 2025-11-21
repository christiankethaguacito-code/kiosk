# Phase 1 Complete - API Testing Guide

## ✅ PHASE 1: BACK-END SETUP - COMPLETED!

Your IoT Campus Directory Kiosk API is now ready for testing!

### 🚀 Server Status
- **Server Running**: http://localhost:8000
- **API Endpoint**: http://localhost:8000/api/v1
- **Database**: SQLite (database/database.sqlite)

---

## 🔑 Test Credentials

### Admin Login
- **Email**: `admin@sksu.edu.ph`
- **Password**: `password123`
- **Role**: superadmin

### Kiosk Admin
- **Email**: `kiosk@sksu.edu.ph`
- **Password**: `kiosk2025`
- **Role**: admin

---

## 📋 API Endpoints to Test

### 🔓 PUBLIC ENDPOINTS (Kiosk Access)

#### 1. Get All Buildings
```bash
GET http://localhost:8000/api/v1/buildings
```

**Expected Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "code": "Administration",
      "name": "Administration Building",
      "description": "Main administrative offices...",
      "image": "/images/buildings/administration.jpg",
      "offices": [
        {
          "id": 1,
          "name": "Office of the Registrar",
          "services": "Enrollment, Transcripts...",
          "head": {
            "name": "Dr. Maria Santos",
            "title": "University Registrar"
          }
        }
      ]
    }
  ]
}
```

#### 2. Get Building by ID
```bash
GET http://localhost:8000/api/v1/buildings/1
```

#### 3. Get Building by Code (for QR/RFID)
```bash
GET http://localhost:8000/api/v1/buildings/code/Administration
```

#### 4. Get All Offices
```bash
GET http://localhost:8000/api/v1/offices
```

#### 5. Get Offices by Building
```bash
GET http://localhost:8000/api/v1/offices/building/1
```

#### 6. Get Office by ID
```bash
GET http://localhost:8000/api/v1/offices/1
```

#### 7. Get All Heads
```bash
GET http://localhost:8000/api/v1/heads
```

---

### 🔐 AUTHENTICATION ENDPOINTS

#### 8. Admin Login
```bash
POST http://localhost:8000/api/v1/admin/login
Content-Type: application/json

{
  "email": "admin@sksu.edu.ph",
  "password": "password123"
}
```

**Expected Response**:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "id": 1,
    "username": "admin",
    "email": "admin@sksu.edu.ph",
    "role": "superadmin"
  }
}
```

#### 9. Check Current Admin (requires login)
```bash
GET http://localhost:8000/api/v1/admin/me
```

#### 10. Admin Logout
```bash
POST http://localhost:8000/api/v1/admin/logout
```

---

### 🔒 PROTECTED ENDPOINTS (Admin Only)

**Note**: You must login first to access these endpoints!

#### 11. Create Building
```bash
POST http://localhost:8000/api/v1/admin/buildings
Content-Type: application/json

{
  "code": "IT_CENTER",
  "name": "Information Technology Center",
  "description": "Modern computer labs and IT services",
  "image": "/images/buildings/it-center.jpg"
}
```

#### 12. Update Building
```bash
PUT http://localhost:8000/api/v1/admin/buildings/1
Content-Type: application/json

{
  "description": "Updated description here"
}
```

#### 13. Delete Building
```bash
DELETE http://localhost:8000/api/v1/admin/buildings/6
```

#### 14. Create Office
```bash
POST http://localhost:8000/api/v1/admin/offices
Content-Type: application/json

{
  "name": "IT Support Office",
  "services": "Technical Support, Network Services",
  "building_id": 1,
  "head_id": null
}
```

#### 15. Create Head
```bash
POST http://localhost:8000/api/v1/admin/heads
Content-Type: application/json

{
  "name": "Dr. John Smith",
  "title": "IT Director",
  "credentials": "PhD in Computer Science, CISSP"
}
```

---

## 🧪 How to Test with Postman

### Step 1: Import Collection
1. Open Postman
2. Create a new Collection named "Campus Kiosk API"
3. Add requests for each endpoint above

### Step 2: Test Public Endpoints
1. Test GET `/api/v1/buildings` - should return 5 buildings
2. Test GET `/api/v1/offices` - should return 8 offices
3. Test GET `/api/v1/heads` - should return 5 heads

### Step 3: Test Authentication
1. POST to `/api/v1/admin/login` with credentials
2. **Important**: In Postman, enable "Send cookies" in the request settings
3. The session cookie will be stored automatically
4. Test GET `/api/v1/admin/me` - should return your admin info

### Step 4: Test Protected Endpoints
1. While logged in, try creating a new building
2. Try updating an existing office
3. Test the validation by sending invalid data

---

## 🔧 Testing with cURL (Command Line)

### Get All Buildings
```bash
curl http://localhost:8000/api/v1/buildings
```

### Login
```bash
curl -X POST http://localhost:8000/api/v1/admin/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@sksu.edu.ph\",\"password\":\"password123\"}" \
  -c cookies.txt
```

### Create Building (requires login cookie)
```bash
curl -X POST http://localhost:8000/api/v1/admin/buildings \
  -H "Content-Type: application/json" \
  -b cookies.txt \
  -d "{\"code\":\"NEW_BLDG\",\"name\":\"New Building\",\"description\":\"Test\"}"
```

---

## 📊 Current Database Contents

### Buildings (5)
- Administration
- College of Teacher Education (CTE)
- College of Health Sciences (CHS)
- College of Criminal Justice Education (CCJE)
- University Library and Resource Center (ULRC)

### Offices (8)
- Registrar's Office
- President's Office
- Finance Office
- CTE Dean's Office
- CTE Faculty Room
- CHS Dean's Office
- CHS Laboratory
- Library Services

### Heads (5)
- Dr. Maria Santos - University Registrar
- Dr. Juan Dela Cruz - University President
- Prof. Ana Garcia - Dean, CTE
- Dr. Pedro Reyes - Dean, CHS
- Engr. Lisa Fernandez - Library Director

### Admins (2)
- admin@sksu.edu.ph
- kiosk@sksu.edu.ph

---

## ✅ Phase 1 Checklist

- [x] SQLite database configured
- [x] Migrations created (buildings, heads, offices, admins)
- [x] Eloquent models with relationships
- [x] API controllers (Building, Office, Head, Admin)
- [x] Public and protected routes
- [x] Session-based authentication
- [x] Database seeded with sample data
- [x] Server running on port 8000

---

## 🎯 Next Steps: Phase 2

You're now ready to move to **Phase 2: Front-End Setup (React Kiosk)**!

The API is fully functional and ready to be consumed by your React application.

### What to do next:
1. Test all API endpoints in Postman
2. Verify data relationships are working
3. Proceed to Phase 2 to build the React kiosk interface

---

## 🐛 Troubleshooting

### Issue: Cannot access API
**Solution**: Make sure the Laravel server is running:
```bash
cd "c:\Users\USER\OneDrive\Desktop\PatisoyFinal\Navi"
C:\xampp\php\php.exe artisan serve
```

### Issue: Session not working
**Solution**: Make sure you're sending cookies with requests (enable in Postman settings)

### Issue: Need to reset database
**Solution**: Run migrations fresh:
```bash
C:\xampp\php\php.exe artisan migrate:fresh --seed
```

---

**Congratulations! Phase 1 is complete! 🎉**
