# ResellPiacenza Theme Setup Guide

Complete setup instructions for the Shoptimizer child theme with ACF and RankMath integration.

---

## Prerequisites

Before starting, ensure you have:

- ✅ WordPress 6.0+
- ✅ WooCommerce plugin installed and activated
- ✅ Shoptimizer parent theme installed
- ✅ This child theme activated

---

## Required Plugins

### 1. Advanced Custom Fields (ACF) - FREE

**Install:**
1. Go to: `Plugins → Add New`
2. Search: "Advanced Custom Fields"
3. Install: **Advanced Custom Fields** by WP Engine
4. Activate the plugin

### 2. RankMath SEO - FREE

**Install:**
1. Go to: `Plugins → Add New`
2. Search: "Rank Math SEO"
3. Install: **Rank Math SEO** by Rank Math
4. Activate and complete the setup wizard

**During RankMath Setup:**
- Enable "WooCommerce" module when prompted
- Enable "Rich Snippets" (Schema) when prompted
- Choose "Product" schema for WooCommerce products

---

## ACF Field Setup

### Step 1: Create Field Group

1. Go to: `Custom Fields → Field Groups` (ACF menu)
2. Click: **Add New**
3. **Title:** `Product Details - ResellPiacenza`

### Step 2: Add Fields

Add these **6 fields** exactly as shown (field names MUST match):

#### Field 1: Material Composition
- **Field Label:** `Material Composition`
- **Field Name:** `material_composition` ⚠️ EXACT
- **Field Type:** `Text Area`
- **Instructions:** `Enter fabric composition (e.g., 100% Cotton, 80% Cotton 20% Polyester)`
- **Rows:** `3`

#### Field 2: Sizing Guide
- **Field Label:** `Sizing Guide`
- **Field Name:** `sizing_guide` ⚠️ EXACT
- **Field Type:** `Text`
- **Instructions:** `Sizing information (e.g., True to size, Runs small, Size up)`
- **Placeholder:** `e.g., True to size`

#### Field 3: Fit Type
- **Field Label:** `Fit Type`
- **Field Name:** `fit_type` ⚠️ EXACT
- **Field Type:** `Select`
- **Instructions:** `Choose the fit style`
- **Choices:** (one per line)
  ```
  slim : Slim Fit
  regular : Regular Fit
  oversized : Oversized Fit
  relaxed : Relaxed Fit
  athletic : Athletic Fit
  ```
- **Allow Null:** Yes
- **Default Value:** `regular`

#### Field 4: Style Notes
- **Field Label:** `Style Notes`
- **Field Name:** `style_notes` ⚠️ EXACT
- **Field Type:** `Text Area`
- **Instructions:** `Additional style details and notes`
- **Rows:** `4`

#### Field 5: Colorway
- **Field Label:** `Colorway`
- **Field Name:** `colorway` ⚠️ EXACT
- **Field Type:** `Text`
- **Instructions:** `Product color/colorway (e.g., Vintage Wash Blue, Black/White)`
- **Placeholder:** `e.g., Vintage Black`

#### Field 6: Gender Target
- **Field Label:** `Gender Target`
- **Field Name:** `gender_target` ⚠️ EXACT
- **Field Type:** `Select`
- **Instructions:** `Target audience gender`
- **Choices:** (one per line)
  ```
  male : Men
  female : Women
  unisex : Unisex
  ```
- **Allow Null:** Yes

### Step 3: Location Rules

**Set where fields appear:**

1. Scroll to **Location** section
2. Set rule: `Post Type` `is equal to` `Product`
3. Click **Add rule group** if you want to limit to specific product categories (optional)

### Step 4: Settings

In the **Settings** section:

- **Style:** `Seamless (no metabox)`
- **Position:** `Normal (after content)`
- **Label Placement:** `Top aligned`
- **Instruction Placement:** `Below label`
- **Active:** Yes

### Step 5: Publish

Click **Publish** button to save the field group.

---

## RankMath Configuration

### Enable Product Schema

1. Go to: `Rank Math → Titles & Meta → Product Archives`
2. Scroll to: **Rich Snippet Type**
3. Select: `Product` (should be default for WooCommerce)
4. Save Changes

### Verify Schema Output

1. Edit any product
2. Scroll down to **Rank Math SEO** meta box
3. Click **Schema** tab
4. Verify: Schema Type is `Product`

**The theme will automatically:**
- Feed your ACF fields into RankMath's Product schema
- Add material, colorway, sizing, etc. to structured data
- No additional configuration needed!

---

## Testing the Setup

### 1. Test Admin UI

1. Go to: `Products → All Products`
2. Edit any product (or create a test product)
3. Scroll down - you should see **Product Details - ResellPiacenza** section
4. Fill in some test data:
   - Material: `100% Cotton`
   - Colorway: `Black`
   - Fit Type: `Regular Fit`
5. **Update** the product

### 2. Test Frontend Display

1. Visit the product page on your website
2. Below the short description, you should see a styled box with:
   ```
   🧵 Material Composition: 100% Cotton
   🎨 Colorway: Black
   👔 Fit Type: Regular Fit
   ```

### 3. Test Schema Output

**Option A: View Source**
1. Visit product page
2. Right-click → View Page Source
3. Search for: `application/ld+json`
4. Verify your custom fields appear in the schema JSON

**Option B: Google Rich Results Test**
1. Go to: https://search.google.com/test/rich-results
2. Enter your product URL
3. Click **Test URL**
4. Verify: No errors, Product schema detected
5. Check if `material`, `color` properties are present

---

## File Structure

Your theme should now have:

```
resellpiacenzashop-theme/
├── inc/
│   ├── config.php                    # Field definitions
│   ├── frontend-display.php          # Display on product pages
│   └── rankmath-integration.php      # SEO schema integration
├── functions.php                      # Main loader
├── style.css                          # Theme metadata
├── SETUP.md                           # This file
└── .gitignore                         # Git configuration
```

---

## Customization

### Change Which Fields Display

Edit: `inc/config.php`

Change `'show_frontend' => false` to hide a field from product pages.

### Change Field Styling

Edit: `inc/frontend-display.php`

Look for the `$custom_css` variable around line 88.

### Change Field Position

Edit: `inc/frontend-display.php` line 20:

```php
add_action('woocommerce_single_product_summary', 'rps_display_product_fields', 25);
```

**Priority values:**
- `5` = After title
- `10` = After price
- `20` = After short description
- `25` = **Current** (between description and add to cart)
- `30` = After add to cart button

### Add New Fields

1. **Add to ACF:** Create new field in ACF field group
2. **Add to config.php:** Add entry to `rps_get_product_fields()` array
3. **Add to rankmath-integration.php:** Add logic to feed into schema

---

## Troubleshooting

### Fields Don't Show in Admin

**Check:**
- ACF plugin is activated
- Field group is published
- Location rule is set to `Post Type = Product`
- Clear any caching plugins

### Fields Don't Show on Frontend

**Check:**
- You've entered data for the fields (they only show if not empty)
- ACF plugin is activated
- Frontend display file exists: `inc/frontend-display.php`
- Check theme is activated (child theme, not parent)

### Schema Not Working

**Check:**
- RankMath plugin is activated
- WooCommerce module enabled in RankMath
- Product schema type selected in Rank Math settings
- Test with Google Rich Results Test tool
- Disable any other schema plugins (Schema Pro, etc.)

### Blank Page Error

**If you get blank pages:**
1. Disable all plugins except WooCommerce, ACF, RankMath
2. Re-enable one by one to find conflict
3. Check PHP error log
4. Verify ACF field names match exactly (case-sensitive)

---

## Support & Documentation

- **ACF Docs:** https://www.advancedcustomfields.com/resources/
- **RankMath Docs:** https://rankmath.com/kb/
- **Shoptimizer Docs:** https://www.commercegurus.com/docs/shoptimizer-theme/
- **WooCommerce Hooks:** https://woocommerce.github.io/code-reference/hooks/hooks.html

---

## Next Steps

1. ✅ Install ACF and RankMath
2. ✅ Create ACF field group with 6 fields
3. ✅ Test on a product
4. ✅ Verify frontend display
5. ✅ Test schema with Google tool
6. 🎉 Start adding product details to your catalog!

---

**Last Updated:** November 2025
**Theme Version:** 1.0.1
