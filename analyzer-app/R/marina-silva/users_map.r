#map bolsonaro's followers
source("shared/users_map.r")

renderUsersMap(c('marina2018', 'marinasilva', 'marinasilva2018', 'marinapresidente', 'cirogomespresidente'), c(0, 10, 150, 500), 
                c("0 - 10", "10 - 150", "150 - 500"), 'Marina Silva')
