import json

# Read the fullinfo.json file
with open('../fullinfo.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

buildings_data = []

for building_item in data:
    building_name = building_item['building'].upper()
    building_id = building_item.get('building_id', '')
    
    # Skip buildings without offices or with empty offices list
    if not building_item.get('offices') or len(building_item['offices']) == 0:
        continue
    
    # Use building_id as image filename (lowercase with extension)
    image_filename = f"{building_id.lower()}.jpg" if building_id else "default.jpg"
    
    offices = []
    for office_item in building_item['offices']:
        office_name = office_item.get('office_name', 'nan')
        
        # Skip offices with 'nan' as name and no services
        if office_name == 'nan' and len(office_item.get('services', [])) == 0:
            continue
        
        # If office name is 'nan', try to use a descriptive name based on head or position
        if office_name == 'nan':
            if office_item.get('position'):
                office_name = office_item['position']
            elif office_item.get('head_of_office'):
                office_name = f"Office of {office_item['head_of_office']}"
            else:
                office_name = "General Office"
        
        office_data = {
            "name": office_name,
            "floor": "",  # Not provided in fullinfo.json
            "head_name": office_item.get('head_of_office') or "",
            "head_title": office_item.get('position') or "",
            "services": office_item.get('services', [])
        }
        
        # Only add offices that have at least a name
        if office_data['name']:
            offices.append(office_data)
    
    # Only add buildings that have at least one valid office
    if offices:
        buildings_data.append({
            "building_name": building_name,
            "image": image_filename,
            "offices": offices
        })

# Write to buildings_data.json
output_path = 'database/seeders/buildings_data.json'
with open(output_path, 'w', encoding='utf-8') as f:
    json.dump(buildings_data, f, indent=2, ensure_ascii=False)

print(f"✓ Conversion complete!")
print(f"✓ Created {len(buildings_data)} buildings")
print(f"✓ Total offices: {sum(len(b['offices']) for b in buildings_data)}")
print(f"✓ Total services: {sum(sum(len(o['services']) for o in b['offices']) for b in buildings_data)}")
print(f"\nBuildings created:")
for building in buildings_data:
    office_count = len(building['offices'])
    service_count = sum(len(o['services']) for o in building['offices'])
    print(f"  - {building['building_name']}: {office_count} offices, {service_count} services")
