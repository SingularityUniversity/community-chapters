# Preparing your file

## Column naming

When importing your file, the Importer has the ability to auto-map headers to fields that have the same label (using the "Map Exact Matches" button). 

Some fields with multiple inputs have different field naming conventions. The defaults are shown below. If you have changed the input names, then you will need to use those custom input names instead.

* Name: `Name (Full)`, `Name (Prefix)`, `Name (First)`, `Name (Middle)`, `Name (Last)`, `Name (Suffix)`
* Address: `Address (Full)`, `Address (Street Address)`, `Address (Address Line 2)`, `Address (City)`, `Address (State / Province)`, `Address (ZIP / Postal Code)`, `Address (Country)`
* __(Not yet supported)__Product Field: `Product Field (Name)`, `Product Field (Price)`, `Product Field (Quantity)`

# Field Formatting

### Note: Not all field types have been implemented

These field types have not yet been added to the Importer, but who knows, they still might work. We're working on adding support for these.

* Product Fields
* Coupon Fields
* Signature Fields
* Quiz Fields

### Name Field

When mapping a Full Name, Prefixes ("Dr", "Ms", "Mr", etc) are not currently supported.

### File Upload Field

#### Single Upload

Single uploads should be the full URL to the file. 

Example:

```
http://example.com/my-pretty-pony.png
```

#### Multiple Uploads

Multiple uploads should be formatted as a JSON array, **without escaping slashes before the `/` character**.
  
Example:

```
["http://example.com/my-pretty-pony.png","http://example.com/my-pretty-kitty.png","http://example.com/my-pretty-doggy.png"]
```

### List Field

#### Single Column Lists

Single column lists should be formatted as a JSON array, with each value enclosed in quotes.

```
["Item 1", "Item 2", "Item 3"]
```

You can also format single columns by separating values with a vertical pipe: `|`:

```
Item 1|Item 2|Item 3
```

#### Multiple Column Lists

Multiple column list fields (with "Enable multiple columns") checked should be formatted as a multidimensional JSON array, with each array containing the same number of items as the number of columns in the field.

Example, with two columns (whitespace added for clarity):

```
[
    ["Row 1 Item 1", "Row 1 Item 2"],
    ["Row 2 Item 1", "Row 2 Item 2"],
    ["Row 2 Item 3", "Row 3 Item 2"]
]
```

Example, with three columns (whitespace added for clarity):

```
[
    ["Row 1 Item 1", "Row 1 Item 2", "Row 1 Item 3"],
    ["Row 2 Item 1", "Row 2 Item 2", "Row 2 Item 3"]
]
```

#### Mapping a Single Column in Multiple Column List Fields

If your field is named "List" and it has three columns, you'll be able to map your input file to each column if you choose: "List 1", "List 2", "List 3". The values should be added as a JSON array.

```
["Row 2 Item 1", "Row 2 Item 2", "Row 2 Item 3"]
```

You can also format single columns by separating values with a vertical pipe: `|`:

```
Row 2 Item 1|Row 2 Item 2|Row 2 Item 3
```

#### Known issues with Multiple Column Lists

* In Gravity Forms, the Field columns must have labels. If the column labels are empty, the values will not be properly stored


### Time Fields

You must use two digits for hours and minutes, you can't use a single number for hours. Example: `9:00` is invalid; use `09:00` instead.

#### If the "Time Format" is 24 hour:

The field should have Hours and Minutes, separated by a colon `:`:
```
HH:MM
```

#### If the "Time Format" is 24 hour:

The field should have Hours and Minutes, separated by a colon `:`, then a space and `AM` or `PM`:

```
HH:MM AM
```

### Phone Fields

If the Phone Field is using the US/Canada format, the validation will check for `(###) ###-####` phone number formats:

```
(800) 555-1212
```

If using the International setting, no validation occurs. If you encounter any import issues, you may want to consider updating your form to use International phone number format during import.

### Entry Notes

Entry notes can be added to imported rows. The notes can be formatted as plain text, or as a JSON array of multiple notes.

Plain text example:
```
This person has been amazing to work with.
```

JSON example:

```
["Called client on June 23 - went well.", "July 4, they were on vacation. I left a message.", "August 12, they send me a pony!"]
```

You can also specify the user to assign to the notes by mapping the "Entry Note Creator" dropdown. This should be the ID of the user to assign the note to. Otherwise, the note will default to being created by the currently logged in user.

## Post Fields

### Post ID
If the Post ID is mapped to be imported, the import will attempt to update an existing post with that ID. *If a post with that Post ID does not exist, none of the post fields will be processed.*
 
If a Post ID is not mapped, then a new post will be created.

### Post Image Field

Post Image data should have values separated by `|:|`, in the following order:

* File URL **(required)**
* Image Title
* Image Caption
* Image Description
  
Example format:

```
http://example.com/my-pretty-pony.png|:|My Pretty Pony|:|This is my pretty pony running in a field|:|What a wonderful day I had with my pony. The field was so green!
```

If a value is empty, you should still include the separator if there is another value after it. In this example, we only want to include the File URL and the Image Description. Note the additional separators:

```
http://example.com/my-pretty-pony.png|:||:||:|What a wonderful day I had with my pony. The field was so green!
```

### Post IDs

If you are passing a Post ID to the importer, the post must exist on the site. If not, the entry will not be created.

### Post Tag Field

Post tags should be formatted with tags separated by commas.

```
Tag 1,Tag 2,Tag 3
```

### Post Category Field

Post categories should be formatted as a JSON array, with the ID of the category as the value.

```
13, 39, 27
```