# users
    - id (int auto)
    - nom (string)
    - prenom (string)
    - email (string unique)
    - password (string)
    - create_at (date)

# recettes
    - id (int auto)
    - titre (string)
    - description (text)
    - url_img (string)
    - create_at (date)
    - id_user (clef etrangere)

# commentaires
    - id (in auto)
    - content (text)
    - id_user (clef etrangere)
    - id_recette (clef etrangere)

# likes
    - id_user (clef etrangere)
    - id_recette (clef etrangere)

# notes
    - id_user (clef etrangere)
    - id_recette (clef etrangere)