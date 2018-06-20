#map bolsonaro's followers
source("shared/users_map.r")

renderUsersMap(c('jairbolsonaro', 'bolsonaro', 'bolsonaro2018', 'jairbolsonaropresidente', 'bolsonaropresidente'), 
                c(0, 10, 150, 500, 1000), c("0 - 10", "10 - 150", "150 - 500", "500 - 1000"), 'Jair Bolsonaro')
