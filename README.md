# Food Mapping & Analytics
**Map food locations. Discover patterns. Make smarter decisions.**

[![Status](https://img.shields.io/badge/status-active-success)](#)
[![Repo](https://img.shields.io/badge/repo-Food--Mapping--and--Analytics-blue)](#)
[![Made with](https://img.shields.io/badge/made%20with-open%20source-black)](#)

> A simple, visual tool for plotting food-related locations (restaurants, groceries, food banks, farms) and generating quick insights from the data.

---

## Preview

<p align="center">
  <img src="docs/images/preview.png" alt="App preview" width="900"/>
</p>

**Live Demo:** `YOUR_DEMO_LINK`  
**Dataset Example:** `data/sample.json`

---

## Why this project?

Food access and availability are geographic problems. This project helps you:

- See **what exists** (and where)
- Identify **gaps** (underserved areas)
- Compare **categories** (grocery vs. restaurants vs. food banks)
- Produce quick **summaries** to support decisions

---

## Core features

### Mapping
- Interactive map with markers
- Click marker → view details
- Search + filter by category

### Analytics
- Counts by category
- Simple location summaries (e.g., top areas)
- Export-ready results (CSV/JSON depending on setup)

### Data
- Works with CSV/JSON style location data
- Suggested required fields: `name`, `category`, `lat`, `lng`

---

## Design system (UI direction)

Use this if you want your app to look modern and readable.

### Colors
| Purpose | Hex |
|--------|-----|
| Primary (Leaf Green) | `#1B7F5A` |
| Accent (Mango) | `#FFA62B` |
| Background | `#F7F8FA` |
| Text | `#1F2937` |
| Muted Text | `#6B7280` |
| Border | `#E5E7EB` |
| Danger | `#DC2626` |

### Typography
- Headings: **Inter** / **Poppins** (600–700)
- Body: **Inter** (400–500)
- Numbers/Stats: Inter (600)

### UI components
- **Top bar**: project name + search
- **Left panel (sidebar)**: filters + legend + quick stats
- **Main canvas**: map
- **Bottom drawer / modal**: location detail

**Layout suggestion**
- Desktop: Sidebar (320px) + Map (fluid)
- Mobile: Map first + collapsible bottom sheet

---

## Suggested screens (UX)

### 1) Map View (Home)
- Search bar: “Search places…”
- Filter chips: Grocery, Restaurant, Food Bank, Farm
- Legend + count by category
- Map markers with clustering (optional)

### 2) Location Details (Modal)
- Name, category badge
- Address + links
- Notes
- “Navigate” button (Google Maps link)

### 3) Analytics View
- Bar chart: count per category
- Table: top areas / zip codes (if you store area info)
- Export: CSV/JSON

---

## Quick start

```bash
git clone https://github.com/JomsCode21/Food-Mapping-and-Analytics.git
cd Food-Mapping-and-Analytics
```

### Install
```bash
# Node example
npm install
```

### Run
```bash
npm run dev
```

---

## Data format (example)

```json
[
  {
    "name": "Community Food Bank",
    "category": "Food Bank",
    "lat": 40.7128,
    "lng": -74.0060,
    "address": "123 Main St",
    "notes": "Open Mon–Fri"
  }
]
```

---

## Roadmap

- [ ] Marker clustering + heatmap
- [ ] “Near me” distance search
- [ ] Import CSV UI
- [ ] Save favorites
- [ ] Better analytics (time, density, accessibility tags)

---

## Contributing

PRs are welcome. Please open an issue first for major changes.

---

## License

Add your preferred license (MIT recommended for open-source).
