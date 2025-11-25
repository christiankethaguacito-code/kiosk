import json
import re

# Read the services_cleaned.json file
with open('services_cleaned.json', 'r', encoding='utf-8') as f:
    services_data = json.load(f)

# Function to clean citation markers
def clean_text(text):
    # Remove [cite_start] and [cite: X] markers
    text = re.sub(r'\[cite_start\]|\[cite:\s*\d+\]', '', text)
    return text.strip()

# Convert to buildings_data format
buildings_data = []

for building_entry in services_data['university_services_directory']:
    building_name = clean_text(building_entry['building'])
    
    # Create building object
    building = {
        "building_name": building_name,
        "image": f"{building_name.lower().replace(' ', '-')}.jpg",
        "offices": []
    }
    
    # Process departments (offices)
    for dept in building_entry['departments']:
        office_name = clean_text(dept['name'])
        
        # Clean services list
        services = [clean_text(service) for service in dept['services']]
        
        office = {
            "name": office_name,
            "floor": "",  # Empty - will be filled manually
            "head_name": "",  # Empty - will be filled manually
            "head_title": "",  # Empty - will be filled manually
            "services": services
        }
        
        building['offices'].append(office)
    
    buildings_data.append(building)

# Write to buildings_data.json
output_path = 'database/seeders/buildings_data.json'
with open(output_path, 'w', encoding='utf-8') as f:
    json.dump(buildings_data, f, indent=2, ensure_ascii=False)

print(f"✓ Converted {len(buildings_data)} buildings")
print(f"✓ Total offices: {sum(len(b['offices']) for b in buildings_data)}")
print(f"✓ Output written to: {output_path}")
print("\nNext steps:")
print("1. Fill in the 'floor', 'head_name', and 'head_title' fields for each office")
print("2. Add building images to storage/app/public/buildings/")
print("3. Run: php artisan db:seed --class=JsonBuildingSeeder")
