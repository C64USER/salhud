SELECT t.name AS pueblo,
t.mapping AS fips,
r.name AS region,
(SELECT
    COUNT(*)
    FROM disease_case c
    WHERE
        c.town_id = t.id
        AND c.year = $year
        AND c.disease_id = $disease_id) AS disease,
(SELECT
    CASE WHEN COUNT(*)/p.population IS NULL THEN 0
    ELSE COUNT(*)/p.population END
    FROM 
    disease_case c,
    town t2,
    population p
    WHERE 
        c.town_id = t.id
        AND c.year = $year
        AND c.town_id = t2.id
        AND p.town_id = t2.id) AS percent
    FROM 
    town t,
    region r
    WHERE r.id = t.region_id;
